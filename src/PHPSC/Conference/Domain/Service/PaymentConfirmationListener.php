<?php
namespace PHPSC\Conference\Domain\Service;

interface PaymentConfirmationListener
{
    /**
     * @var string
     */
    const CONFIRM_PAYMENT = 'confirm';

    /**
     * @param PaymentEvent $event
     */
    public function confirm(PaymentEvent $event);
}
