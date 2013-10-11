<?php
namespace PHPSC\Conference\Domain\Service;

use InvalidArgumentException;
use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Payment;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Repository\AttendeeRepository;
use PHPSC\Conference\Infra\Email\DeliveryService;

class AttendeeManagementService
{
    /**
     * @var AttendeeRepository
     */
    private $repository;

    /**
     * @var TalkManagementService
     */
    private $talkService;

    /**
     * @var DeliveryService
     */
    private $deliveryService;

    /**
     * @param AttendeeRepository $repository
     * @param TalkManagementService $talkService
     * @param DeliveryService $deliveryService
     */
    public function __construct(
        AttendeeRepository $repository,
        TalkManagementService $talkService,
        DeliveryService $deliveryService
    ) {
        $this->repository = $repository;
        $this->talkService = $talkService;
        $this->deliveryService = $deliveryService;
    }

    /**
     * @param int $id
     * @return Attendee
     */
    public function findById($id)
    {
        return $this->repository->findOneById((int) $id);
    }

    /**
     * @param Event $event
     * @param User $user
     * @return multitype:Attendee
     */
    public function findByEventAndUser(Event $event, User $user)
    {
        return $this->repository->findByEventAndUser($event, $user);
    }

    /**
     * @param Event $event
     * @param User $user
     * @return Attendee
     */
    public function findActiveRegistration(Event $event, User $user)
    {
        foreach ($this->findByEventAndUser($event, $user) as $attendee) {
            if ($attendee->isActive()) {
                return $attendee;
            }
        }

        return null;
    }

    /**
     * @param Event $event
     * @param User $user
     * @return boolean
     */
    public function hasAnActiveRegistration(Event $event, User $user)
    {
        return $this->findActiveRegistration($event, $user) !== null;
    }

    /**
     * @param Event $event
     * @param User $user
     * @param boolean $isStudent
     * @return Attendee
     */
    public function create(Event $event, User $user, $isStudent = false)
    {
        if ($this->hasAnActiveRegistration($event, $user)) {
            throw new InvalidArgumentException(
                'Você já possui uma inscrição neste evento'
            );
        }

        $attendee = $this->createAttendee($event, $user, $isStudent);
        $this->repository->append($attendee);

        $message = $this->deliveryService->getMessageFromTemplate(
            $isStudent ? 'StudentRegistration' : 'Registration',
            array(
                'user_name' => $user->getName(),
                'event_name' => $event->getName(),
                'event_location' => $event->getLocation()->getName()
            )
        );

        $message->setTo($user->getEmail());
        $this->deliveryService->send($message);

        return $attendee;
    }

    /**
     * @param Attendee $attendee
     */
    public function appendPayment(Attendee $attendee, Payment $payment)
    {
        $attendee->addPayment($payment);

        $this->repository->update($attendee);
    }

    /**
     * @param Event $event
     * @param User $user
     * @param boolean $isStudent
     * @return Attendee
     */
    protected function createAttendee(Event $event, User $user, $isStudent)
    {
        if ($this->talkService->userHasAnyApprovedTalk($user, $event)) {
            return Attendee::createSpeakerAttendee($event, $user);
        }

        if ($isStudent) {
            return Attendee::createStudentAttendee($event, $user);
        }

        return Attendee::createRegularAttendee($event, $user);
    }

    /**
     * @param Payment $payment
     * @return Attendee
     */
    public function confirmPayment(Payment $payment)
    {
        $attendee = $this->repository->findOneByPayment($payment);

        if ($attendee === null) {
            return ;
        }

        $attendee->approve();

        $this->repository->update($attendee);

        $message = $this->deliveryService->getMessageFromTemplate(
            'PaymentConfirmation',
            array(
                'user_name' => $attendee->getUser()->getName(),
                'event_name' => $attendee->getEvent()->getName()
            )
        );

        $message->setTo($attendee->getUser()->getEmail());
        $this->deliveryService->send($message);

        return $attendee;
    }

    /**
     * @param Event $event
     * @return array
     */
    public function getSummary(Event $event)
    {
        return array(
            'inscritos' => $this->repository->getCountOfActiveAttendee($event),
            'presentes' => $this->repository->getCountOfArrivedAttendee($event)
        );
    }
}
