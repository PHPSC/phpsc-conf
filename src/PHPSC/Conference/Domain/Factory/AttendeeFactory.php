<?php
namespace PHPSC\Conference\Domain\Factory;

use DateTime;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Service\TalkManagementService;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class AttendeeFactory
{
    /**
     * @var TalkManagementService
     */
    private $talkService;

    /**
     * @param TalkManagementService $talkService
     */
    public function __construct(TalkManagementService $talkService)
    {
        $this->talkService = $talkService;
    }

    /**
     * @param Event $event
     * @param User $user
     * @param boolean $isStudent
     * @param string $registrationType
     * @return Attendee
     */
    public function create(Event $event, User $user, $isStudent, $registrationType)
    {
        $attendee = new Attendee();

        $attendee->setEvent($event);
        $attendee->setUser($user);
        $attendee->setStatus(Attendee::WAITING_PAYMENT);
        $attendee->setArrived(false);
        $attendee->setStudentRegistration($isStudent);
        $attendee->setRegistrationType($registrationType);
        $attendee->setCreationTime(new DateTime());

        if ($this->talkService->userHasAnyApprovedTalk($user, $event)) {
            $attendee->setStatus(Attendee::PAYMENT_NOT_NECESSARY);
            $attendee->setRegistrationType(Attendee::WORKSHOPS_AND_TALKS);
        }

        return $attendee;
    }
}
