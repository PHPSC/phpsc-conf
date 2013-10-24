<?php
namespace PHPSC\Conference\Domain\Service;


use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\User;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class AttendeeRegistrationService
{
    /**
     * @var AttendeeManagementService
     */
    protected $attendeeManager;

    /**
     * @var PaymentManagementService
     */
    protected $paymentManager;

    /**
     * @var TalkManagementService
     */
    private $talkService;

    /**
     * @param AuthenticationService $authService
     * @param AttendeeManagementService $talkManager
     * @param PaymentManagementService $paymentManager
     * @param TalkManagementService $talkService
     */
    public function __construct(
        AttendeeManagementService $attendeeManager,
        PaymentManagementService $paymentManager,
        TalkManagementService $talkService
    ) {
        $this->attendeeManager = $attendeeManager;
        $this->paymentManager = $paymentManager;
        $this->talkService = $talkService;
    }

    /**
     * @param boolean $isStudent
     * @param string $redirectTo
     * @return string
     */
    public function create(Event $event, User $user, $isStudent, $redirectTo)
    {
        $attendee = $this->attendeeManager->create(
            $event,
            $user,
            $isStudent
        );

        if ($attendee->isWaitingForPayment()) {
            return $this->createPayment($attendee, $redirectTo);
        }

        //TODO Refactor this!
        return array(
            'data' => array(
                'id' => $attendee->getId(),
                'redirectTo' => $redirectTo
            )
        );
    }

    /**
     * @param Event $event
     * @param User $user
     * @param string $redirectTo
     * @return array
     */
    public function resendPayment(Event $event, User $user, $redirectTo)
    {
        //TODO Refactor this!
        $attendee = $this->attendeeManager->findActiveRegistration(
            $event,
            $user
        );

        if ($attendee === null) {
            return array(
                'error' => 'Você não possui inscrição ativa neste evento!'
            );
        }

        if (!$attendee->isWaitingForPayment()) {
            return array(
                'error' => 'Sua inscrição não necessita de pagamento!'
            );
        }

        return $this->createPayment($attendee, $redirectTo);
    }

    /**
     * @param Attendee $attendee
     * @param string $redirectTo
     * @return array
     */
    protected function createPayment(Attendee $attendee, $redirectTo)
    {
        $payment = $this->paymentManager->create(
            $attendee->getEvent()->getRegistrationCost($attendee->getUser(), $this->talkService),
            $this->getItemDescription($attendee->getEvent())
        );

        $this->attendeeManager->appendPayment($attendee, $payment);

        $paymentResponse = $this->paymentManager->requestPayment(
            $payment,
            $attendee->getUser()->getEmail(),
            $redirectTo
        );

        //TODO Refactor this!
        return array(
            'data' => array(
                'id' => $attendee->getId(),
                'redirectTo' => $paymentResponse->getRedirectionUrl()
            )
        );
    }

    /**
     * @param Event $event
     * @return string
     */
    protected function getItemDescription(Event $event)
    {
        $description = $this->talkService->eventHasAnyApprovedTalk($event)
                       ? 'Inscrição Regular - '
                       : 'Inscrição Antecipada - ';

        return $description . $event->getName();
    }
}
