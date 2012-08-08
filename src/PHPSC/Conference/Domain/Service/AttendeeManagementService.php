<?php
namespace PHPSC\Conference\Domain\Service;

use \PHPSC\Conference\Domain\Repository\AttendeeRepository;
use \PHPSC\Conference\Domain\Entity\Attendee;
use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\User;
use \InvalidArgumentException;

class AttendeeManagementService
{
    /**
     * @var \PHPSC\Conference\Domain\Repository\AttendeeRepository
     */
    private $repository;

    /**
     * @var \PHPSC\Conference\Domain\Service\TalkManagementService
     */
    private $talkService;

    /**
     * @param \PHPSC\Conference\Domain\Repository\AttendeeRepository $repository
     * @param \PHPSC\Conference\Domain\Service\TalkManagementService $talkService
     */
    public function __construct(
        AttendeeRepository $repository,
        TalkManagementService $talkService
    ) {
        $this->repository = $repository;
        $this->talkService = $talkService;
    }

    /**
     * @param int $id
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public function findById($id)
    {
        return $this->repository->findOneById((int) $id);
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return multitype:\PHPSC\Conference\Domain\Entity\Attendee
     */
    public function findByEventAndUser(Event $event, User $user)
    {
        return $this->repository->findByEventAndUser($event, $user);
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return \PHPSC\Conference\Domain\Entity\Attendee
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
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return boolean
     */
    public function hasAnActiveRegistration(Event $event, User $user)
    {
        return $this->findActiveRegistration($event, $user) !== null;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param boolean $isStudent
     * @return \PHPSC\Conference\Domain\Entity\Attendee
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

        return $attendee;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param boolean $isStudent
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    protected function createAttendee(Event $event, User $user, $isStudent)
    {
        if ($this->talkService->userHasAnyApprovedTalk($user, $event)) {
            return Attendee::createSpeakerAttendee($event, $user);
        }

        if ($isStudent) {
            return Attendee::createStudentAttendee($event, $user);
        }

        return Attendee::createRegularAttendee(
            $event,
            $user,
            $event->getRegistrationCost($user, $this->talkService)
        );
    }

    /**
     * @param int $attendeeId
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public function confirmPayment($attendeeId)
    {
        $attendee = $this->findById($attendeeId);

        $attendee->approve();
        $this->repository->update($attendee);

        return $attendee;
    }

    /**
     * @param int $attendeeId
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public function cancelRegistration($attendeeId)
    {
        $attendee = $this->findById($attendeeId);

        $attendee->cancel();
        $this->repository->update($attendee);

        return $attendee;
    }
}