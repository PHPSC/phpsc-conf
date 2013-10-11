<?php
namespace PHPSC\Conference\Domain\Service;

use Doctrine\Common\EventArgs;
use PHPSC\Conference\Domain\Entity\Payment;

class PaymentEvent extends EventArgs
{
    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
