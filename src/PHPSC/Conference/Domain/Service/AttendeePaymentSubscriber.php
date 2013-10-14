<?php
namespace PHPSC\Conference\Domain\Service;

use Doctrine\Common\EventSubscriber;

class AttendeePaymentSubscriber implements PaymentConfirmationListener, EventSubscriber
{
    /**
     * @var AttendeeManagementService
     */
    protected $manager;

    public function __construct(AttendeeManagementService $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(PaymentConfirmationListener::CONFIRM_PAYMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function confirm(PaymentEvent $event)
    {
        $this->manager->confirmPayment($event->getPayment());
    }
}
