<?php
namespace PHPSC\Conference\Domain\Service;

use \PHPSC\PagSeguro\ValueObject\Payment\PaymentRequest;
use \PHPSC\Conference\Domain\Entity\Attendee;
use \PHPSC\PagSeguro\NotificationService;
use \PHPSC\PagSeguro\ValueObject\Sender;
use \PHPSC\PagSeguro\ValueObject\Item;
use \PHPSC\PagSeguro\PaymentService;
use \InvalidArgumentException;

class PaymentManagementService
{
    /**
     * @var \PHPSC\PagSeguro\NotificationService
     */
    private $notificationService;

    /**
     * @var \PHPSC\PagSeguro\PaymentService
     */
    private $paymentService;

    /**
     * @var \PHPSC\Conference\Domain\Service\TalkManagementService
     */
    private $talkService;

    /**
     * @var \PHPSC\Conference\Domain\Service\AttendeeManagementService
     */
    private $attendeeService;

    /**
     * @param \PHPSC\PagSeguro\NotificationService $notificationService
     * @param \PHPSC\PagSeguro\PaymentService $paymentService
     * @param \PHPSC\Conference\Domain\Service\TalkManagementService $talkService
     * @param \PHPSC\Conference\Domain\Service\AttendeeManagementService $attendeeService
     */
    public function __construct(
        NotificationService $notificationService,
        PaymentService $paymentService,
        TalkManagementService $talkService,
        AttendeeManagementService $attendeeService
    ) {
        $this->notificationService = $notificationService;
        $this->paymentService = $paymentService;
        $this->talkService = $talkService;
        $this->attendeeService = $attendeeService;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Attendee $attendee
     * @param string $redirectTo
     * @return \PHPSC\PagSeguro\ValueObject\Payment\PaymentResponse
     */
    public function create(Attendee $attendee, $redirectTo = null)
    {
        if (!$attendee->isPaymentRequired()) {
            throw new InvalidArgumentException(
                'O pagamento não é necessário para esta inscrição'
            );
        }

        $description = $this->talkService->eventHasAnyApprovedTalk($attendee->getEvent())
                       ? 'Inscrição Regular - '
                       : 'Inscrição Antecipada - ';

        $description .= $attendee->getEvent()->getName();

        return $this->paymentService->send(
            new PaymentRequest(
                array(
                    new Item(
                        1,
                        $description,
                        $attendee->getCost()
                    )
                ),
                $attendee->getId(),
                new Sender($attendee->getUser()->getEmail()),
                null,
                null,
                $redirectTo,
                1
            )
        );
    }

    /**
     * @param string $code
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public function updatePaymentStatus($code)
    {
        $transaction = $this->notificationService->getByCode($code);

        if ($transaction->isPaid()) {
            return $this->attendeeService->confirmPayment(
                $transaction->getReference()
            );
        }

        if ($transaction->isReturned() || $transaction->isCancelled()) {
            return $this->attendeeService->cancelRegistration(
                $transaction->getReference()
            );
        }
    }
}
