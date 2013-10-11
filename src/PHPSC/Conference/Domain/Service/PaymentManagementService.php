<?php
namespace PHPSC\Conference\Domain\Service;

use InvalidArgumentException;
use Doctrine\Common\EventManager;
use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Entity\Payment;
use PHPSC\Conference\Domain\Repository\PaymentRepository;
use PHPSC\PagSeguro\NotificationService;
use PHPSC\PagSeguro\PaymentService;
use PHPSC\PagSeguro\ValueObject\Item;
use PHPSC\PagSeguro\ValueObject\Payment\PaymentRequest;
use PHPSC\PagSeguro\ValueObject\Payment\PaymentResponse;
use PHPSC\PagSeguro\ValueObject\Sender;
use PHPSC\Conference\Infra\Persistence\EntityDoesNotExistsException;

class PaymentManagementService
{
    /**
     * @var PaymentRepository
     */
    private $repository;

    /**
     * @var NotificationService
     */
    private $notificationService;

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @param PaymentRepository $repository
     * @param NotificationService $notificationService
     * @param PaymentService $paymentService
     * @param EventManager $eventManager
     */
    public function __construct(
        PaymentRepository $repository,
        NotificationService $notificationService,
        PaymentService $paymentService,
        EventManager $eventManager
    ) {
        $this->repository = $repository;
        $this->notificationService = $notificationService;
        $this->paymentService = $paymentService;
        $this->eventManager = $eventManager;
    }

    /**
     * @param float $cost
     * @param string $description
     * @return PaymentResponse
     */
    public function create($cost, $description)
    {
        $payment = Payment::create($cost, $description);
        $this->repository->append($payment);

        return $payment;
    }

    /**
     * @param Payment $payment
     * @param string $email
     * @param string $redirectTo
     * @return PaymentResponse
     */
    public function requestPayment(Payment $payment, $email, $redirectTo)
    {
        $response = $this->paymentService->send(
            new PaymentRequest(
                array(
                    new Item(
                        1,
                        $payment->getDescription(),
                        $payment->getCost()
                    )
                ),
                $payment->getId(),
                new Sender($email),
                null,
                null,
                $redirectTo,
                1
            )
        );

        $payment->setCode($response->getCode());
        $this->repository->update($payment);

        return $response;
    }

    /**
     * @param Payment $payment
     */
    protected function approvePayment(Payment $payment)
    {
        $payment->approve();

        $this->repository->update($payment);
    }

    /**
     * @param Payment $payment
     */
    protected function cancelPayment(Payment $payment)
    {
        $payment->cancel();

        $this->repository->update($payment);
    }

    /**
     * @param string $code
     * @return Attendee
     */
    public function updatePaymentStatus($code)
    {
        $transaction = $this->notificationService->getByCode($code);
        $payment = $this->repository->findOneByCode($transaction->getCode());

        if ($payment === null) {
            throw new EntityDoesNotExistsException('Pagamento não encontrado');
        }

        if ($transaction->isPaid()) {
            $this->approvePayment($payment);

            $this->eventManager->dispatchEvent(
                PaymentConfirmationListener::CONFIRM_PAYMENT,
                new PaymentEvent($payment)
            );
        }

        if ($transaction->isReturned() || $transaction->isCancelled()) {
            $this->cancelPayment($payment);

            $this->eventManager->dispatchEvent(
                PaymentCancellationListener::CANCEL_PAYMENT,
                new PaymentEvent($payment)
            );
        }
    }
}
