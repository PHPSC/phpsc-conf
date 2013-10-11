<?php
namespace PHPSC\Conference\Domain\Service;

interface PaymentCancellationListener
{
    /**
     * @var string
     */
    const CANCEL_PAYMENT = 'cancel';

    /**
     * @param PaymentEvent $event
     */
    public function cancel(PaymentEvent $event);
}
