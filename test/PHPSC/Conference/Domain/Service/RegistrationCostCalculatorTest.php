<?php
namespace PHPSC\Conference\Domain\Service;

use DateTime;
use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\RegistrationInfo;
use PHPSC\Conference\Domain\Entity\DiscountCoupon;
use PHPSC\Conference\Domain\Entity\User;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class RegistrationCostCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TalkManagementService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $talkManager;

    /**
     * @var RegistrationCostCalculator
     */
    private $calculator;

    /**
     * @var Attendee|\PHPUnit_Framework_MockObject_MockObject
     */
    private $attendee;

    /**
     * @var Event|\PHPUnit_Framework_MockObject_MockObject
     */
    private $event;

    /**
     * @var User|\PHPUnit_Framework_MockObject_MockObject
     */
    private $user;

    protected function setUp()
    {
        $this->talkManager = $this->getMock(TalkManagementService::class, [], [], '', false);
        $this->calculator = new RegistrationCostCalculator($this->talkManager);
        $this->attendee = $this->getMock(Attendee::class, [], [], '', false);
        $this->event = $this->getMock(Event::class, [], [], '', false);
        $this->user = $this->getMock(User::class, [], [], '', false);

        $info = $this->getMock(RegistrationInfo::class);

        $info->expects($this->any())
             ->method('getWorkshopsPrice')
             ->willReturn(40);

        $info->expects($this->any())
             ->method('getTalksPrice')
             ->willReturn(60);

        $info->expects($this->any())
             ->method('getStudentDiscount')
             ->willReturn(50);

        $info->expects($this->any())
             ->method('getCostVariation')
             ->willReturn(25);

        $this->event->expects($this->any())
                    ->method('getRegistrationInfo')
                    ->willReturn($info);

        $this->attendee->expects($this->any())
                       ->method('getEvent')
                       ->willReturn($this->event);

        $this->attendee->expects($this->any())
                       ->method('getUser')
                       ->willReturn($this->user);
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     */
    public function getBaseCostShouldReturnZeroWhenEventHasntAttendeeRegistration()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(false);

        $this->assertEquals(0, $this->calculator->getBaseCost($this->event, $this->user, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfTalksDayOnlyWithDiscountWhenEventHasntApprovedTalks()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(false);

        $this->assertEquals(45, $this->calculator->getBaseCost($this->event, $this->user, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfBothDaysWithDiscountWhenEventHasntApprovedTalks()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(false);

        $this->assertEquals(75, $this->calculator->getBaseCost($this->event, $this->user, new DateTime(), false));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfTalksDayOnlyWithDiscountWhenUserHasTalksAndItsSpeakerPromotionPeriod()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isSpeakerPromotionalPeriod')
                    ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('userHasAnyTalk')
                          ->willReturn(true);

        $this->assertEquals(45, $this->calculator->getBaseCost($this->event, $this->user, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfBothDaysWithDiscountWhenUserHasTalksAndItsSpeakerPromotionPeriod()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isSpeakerPromotionalPeriod')
                    ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('userHasAnyTalk')
                          ->willReturn(true);

        $this->assertEquals(75, $this->calculator->getBaseCost($this->event, $this->user, new DateTime(), false));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfTalksDayOnlyWithFineWhenIsLateRegistrationPeriod()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->assertEquals(75, $this->calculator->getBaseCost($this->event, $this->user, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfBothDaysWithFineWhenIsLateRegistrationPeriod()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->assertEquals(125, $this->calculator->getBaseCost($this->event, $this->user, new DateTime(), false));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfTalksDayOnlyWhenIsRegularRegistrationPeriod()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(false);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->assertEquals(60, $this->calculator->getBaseCost($this->event, $this->user, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     */
    public function getBaseCostShouldReturnTheCostOfBothDaysWhenIsRegularRegistrationPeriod()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(false);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->assertEquals(100, $this->calculator->getBaseCost($this->event, $this->user, new DateTime(), false));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getStudentDiscount
     */
    public function getCostShouldReturnBaseCostForTalksDayOnlyIfMatchesWithAttendeeRegistrationType()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(false);

        $this->attendee->expects($this->any())
                       ->method('isStudentRegistration')
                       ->willReturn(false);

        $this->attendee->expects($this->any())
                       ->method('canAttendTalksDayOnly')
                       ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('userHasAnyTalk')
                          ->willReturn(false);

        $this->assertEquals(60, $this->calculator->getCost($this->attendee, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getStudentDiscount
     */
    public function getCostShouldReturnBaseCostForBothDaysIfMatchesWithAttendeeRegistrationType()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(false);

        $this->attendee->expects($this->any())
                       ->method('isStudentRegistration')
                       ->willReturn(false);

        $this->attendee->expects($this->any())
                       ->method('canAttendTalksDayOnly')
                       ->willReturn(false);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('userHasAnyTalk')
                          ->willReturn(false);

        $this->assertEquals(100, $this->calculator->getCost($this->attendee, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getStudentDiscount
     */
    public function getCostShouldApplyStudentDiscountWhenItsAStudentRegistration()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(false);

        $this->attendee->expects($this->any())
                       ->method('isStudentRegistration')
                       ->willReturn(true);

        $this->attendee->expects($this->any())
                       ->method('canAttendTalksDayOnly')
                       ->willReturn(false);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->talkManager->expects($this->any())
                          ->method('userHasAnyTalk')
                          ->willReturn(false);

        $this->assertEquals(50, $this->calculator->getCost($this->attendee, new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::__construct
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getBaseCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getVariation
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getCost
     * @covers PHPSC\Conference\Domain\Service\RegistrationCostCalculator::getStudentDiscount
     */
    public function getCostShouldApplyDiscountWhenAttendeeHasACoupon()
    {
        $this->event->expects($this->any())
                    ->method('hasAttendeeRegistration')
                    ->willReturn(true);

        $discount = $this->getMock(DiscountCoupon::class);

        $discount->expects($this->any())
                 ->method('applyDiscountTo')
                 ->willReturn(50);

        $this->attendee->expects($this->any())
                       ->method('isStudentRegistration')
                       ->willReturn(false);

        $this->attendee->expects($this->any())
                       ->method('canAttendTalksDayOnly')
                       ->willReturn(false);

        $this->attendee->expects($this->any())
                       ->method('getDiscount')
                       ->willReturn($discount);

        $this->talkManager->expects($this->any())
                          ->method('eventHasAnyApprovedTalk')
                          ->willReturn(true);

        $this->event->expects($this->any())
                    ->method('isLateRegistrationPeriod')
                    ->willReturn(false);

        $this->assertEquals(50, $this->calculator->getCost($this->attendee, new DateTime()));
    }
}
