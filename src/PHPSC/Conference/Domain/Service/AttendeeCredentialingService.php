<?php
namespace PHPSC\Conference\Domain\Service;

use PHPSC\Conference\Domain\Repository\AttendeeRepository;
use PHPSC\Conference\Domain\Entity\Attendee;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class AttendeeCredentialingService
{
    /**
     * @var AttendeeRepository
     */
    protected $repository;

    /**
     * @var PaymentManagementService
     */
    protected $paymentService;

    /**
     * @var AttendeeRegistrationService
     */
    protected $registrationService;

    /**
     * @param AttendeeRepository $repository
     * @param AttendeeRegistrationService $registrationService
     * @param PaymentManagementService $paymentService
     */
    public function __construct(
        AttendeeRepository $repository,
        AttendeeRegistrationService $registrationService,
        PaymentManagementService $paymentService
    ) {
        $this->repository = $repository;
        $this->registrationService = $registrationService;
        $this->paymentService = $paymentService;
    }

    /**
     * @param Attendee $attendee
     */
    public function confirm(Attendee $attendee)
    {
        $attendee->setStatus(Attendee::APPROVED);
        $attendee->setArrived(true);

        $this->repository->update($attendee);
    }

    /**
     * @param Attendee $attendee
     */
    public function registerPayment(Attendee $attendee)
    {
        $attendee->setStatus(Attendee::WAITING_PAYMENT);
        $this->registrationService->createPayment($attendee, '');
        $this->paymentService->approvePayment($attendee->getLastPayment(), false);

        $this->confirm($attendee);
    }
}
