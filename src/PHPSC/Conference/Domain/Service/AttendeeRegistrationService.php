<?php
namespace PHPSC\Conference\Domain\Service;


use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Entity\DiscountCoupon;

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
     * @param DiscountCoupon $coupon
     * @return string
     */
    public function create(
        Event $event,
        User $user,
        $isStudent,
        $redirectTo,
        DiscountCoupon $coupon = null
    ) {
        $attendee = $this->attendeeManager->create(
            $event,
            $user,
            $isStudent,
            $coupon
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
    public function createPayment(Attendee $attendee, $redirectTo)
    {
        $payment = $this->paymentManager->create(
            $attendee->getEvent()->getRegistrationCost($attendee, $this->talkService),
            $this->getItemDescription($attendee->getEvent())
        );

        $this->attendeeManager->appendPayment($attendee, $payment);

        if ($attendee->getEvent()->isLateRegistrationPeriod($payment->getCreationTime())) {
            return array(
                'data' => array(
                    'id' => $attendee->getId(),
                    'redirectTo' => $redirectTo
                )
            );
        }

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
        if (!$this->talkService->eventHasAnyApprovedTalk($event)) {
            return 'Inscrição Antecipada - ' . $event->getName();
        }

        if ($event->isLateRegistrationPeriod(new \DateTime())) {
            return 'Inscrição Tardia - ' . $event->getName();
        }

        return 'Inscrição Regular - ' . $event->getName();
    }
}
