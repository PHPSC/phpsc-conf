<?php
namespace PHPSC\Conference\Domain\Service;

use DateTime;
use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\User;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class RegistrationCostCalculator
{
    /**
     * @var TalkManagementService
     */
    private $talkManager;

    /**
     * @param TalkManagementService $talkManager
     */
    public function __construct(TalkManagementService $talkManager)
    {
        $this->talkManager = $talkManager;
    }

    /**
     * @param Event $event
     * @param boolean $talksOnly
     * @param DateTime $at
     *
     * @return number
     */
    public function getBaseCost(Event $event, User $user, DateTime $at, $talksOnly = true)
    {
        if (!$event->hasAttendeeRegistration()) {
            return 0;
        }

        $info = $event->getRegistrationInfo();
        $cost = $info->getTalksPrice();

        if (!$talksOnly) {
            $cost += $info->getWorkshopsPrice();
        }

        if ($variation = $this->getVariation($cost, $event, $user, $at)) {
            $cost += $variation;
        }

        return $cost;
    }

    /**
     * @param float $cost
     * @param Event $event
     * @param User $user
     * @param DateTime $now
     *
     * @return number
     */
    protected function getVariation($cost, Event $event, User $user, DateTime $now)
    {
        $variation = $cost * $event->getRegistrationInfo()->getCostVariation() / 100;

        if (!$this->talkManager->eventHasAnyApprovedTalk($event)
            || ($event->isSpeakerPromotionalPeriod($now) && $this->talkManager->userHasAnyTalk($user, $event))) {
            return $variation * -1;
        }

        if ($event->isLateRegistrationPeriod($now)) {
            return $variation;
        }
    }

    /**
     * @param Attendee $attendee
     * @param DateTime $at
     *
     * @return float
     */
    public function getCost(Attendee $attendee, DateTime $at)
    {
        $cost = $this->getBaseCost(
            $attendee->getEvent(),
            $attendee->getUser(),
            $at,
            $attendee->canAttendTalksDayOnly()
        );

        if ($studentDiscount = $this->getStudentDiscount($cost, $attendee)) {
            $cost -= $studentDiscount;
        }

        if ($coupon = $attendee->getDiscount()) {
            $cost = $coupon->applyDiscountTo($cost);
        }

        return $cost;
    }

    /**
     * @param float $cost
     * @param Attendee $attendee
     *
     * @return number
     */
    protected function getStudentDiscount($cost, Attendee $attendee)
    {
        if ($attendee->isStudentRegistration()) {
            return $cost * $attendee->getEvent()->getRegistrationInfo()->getStudentDiscount() / 100;
        }
    }
}
