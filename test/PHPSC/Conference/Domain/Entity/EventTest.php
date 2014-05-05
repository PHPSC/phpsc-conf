<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegistrationInfo|\PHPUnit_Framework_MockObject_MockObject
     */
    private $registrationInfo;

    protected function setUp()
    {
        $this->registrationInfo = $this->getMock(RegistrationInfo::class, [], [], '', false);
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     */
    public function hasAttendeeRegistrationShouldReturnFalseWhenRegistrationInfoIsNull()
    {
        $event = new Event();

        $this->assertFalse($event->hasAttendeeRegistration());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     */
    public function hasAttendeeRegistrationShouldReturnTrueWhenRegistrationInfoIsntNull()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);

        $this->assertTrue($event->hasAttendeeRegistration());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     */
    public function isRegistrationPeriodShouldReturnFalseWhenEventHasntAttendeeRegistration()
    {
        $event = new Event();

        $this->assertFalse($event->isRegistrationPeriod(new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     */
    public function isRegistrationPeriodShouldReturnFalseWhenGivenDateIsBeforeTheRegistrationPeriod()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->assertFalse($event->isRegistrationPeriod(new DateTime('2014-05-04 23:59:59')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     */
    public function isRegistrationPeriodShouldReturnFalseWhenGivenDateIsAfterTheRegistrationPeriod()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-05-05 23:59:59'));

        $this->assertFalse($event->isRegistrationPeriod(new DateTime('2014-05-06 00:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     */
    public function isRegistrationPeriodShouldReturnTrueWhenGivenDateIsBetweenTheRegistrationPeriod()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-05-05 23:59:59'));

        $this->assertTrue($event->isRegistrationPeriod(new DateTime('2014-05-05 10:20:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     */
    public function isEventPeriodShouldReturnFalseWhenGivenDateIsBeforeTheEventStart()
    {
        $event = new Event();
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->assertFalse($event->isEventPeriod(new DateTime('2014-08-28 23:59:59')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     */
    public function isEventPeriodShouldReturnFalseWhenGivenDateIsAfterTheEventEnd()
    {
        $event = new Event();
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->assertFalse($event->isEventPeriod(new DateTime('2014-08-31 00:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     */
    public function isEventPeriodShouldReturnTrueWhenGivenDateIsAfterTheEventEnd()
    {
        $event = new Event();
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->assertTrue($event->isEventPeriod(new DateTime('2014-08-30 10:10:06')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::isLateRegistrationPeriod
     */
    public function isLateRegistrationPeriodShouldReturnTrueWhenIsRegistrationAndEventPeriods()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-08-30 14:00:00'));

        $this->assertTrue($event->isLateRegistrationPeriod(new DateTime('2014-08-30 12:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::isLateRegistrationPeriod
     */
    public function isLateRegistrationPeriodShouldReturnFalseWhenIsRegistrationPeriodButNotEventPeriod()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-08-30 14:00:00'));

        $this->assertFalse($event->isLateRegistrationPeriod(new DateTime('2014-08-28 12:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::isLateRegistrationPeriod
     */
    public function isLateRegistrationPeriodShouldReturnFalseWhenIsNotRegistrationPeriodButIsEventPeriod()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-08-30 14:00:00'));

        $this->assertFalse($event->isLateRegistrationPeriod(new DateTime('2014-08-30 15:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegularRegistrationPeriod
     */
    public function isRegularRegistrationPeriodShouldReturnTrueWhenIsRegistrationPeriodButIsNotEventPeriod()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-08-30 14:00:00'));

        $this->assertTrue($event->isRegularRegistrationPeriod(new DateTime('2014-08-28 23:59:59')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegularRegistrationPeriod
     */
    public function isRegularRegistrationPeriodShouldReturnFalseWhenIsRegistrationAndEventPeriods()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-08-30 14:00:00'));

        $this->assertFalse($event->isRegularRegistrationPeriod(new DateTime('2014-08-29 10:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::getRegistrationInfo
     * @covers PHPSC\Conference\Domain\Entity\Event::hasAttendeeRegistration
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegistrationPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::getEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setEndDate
     * @covers PHPSC\Conference\Domain\Entity\Event::getStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::setStartDate
     * @covers PHPSC\Conference\Domain\Entity\Event::isEventPeriod
     * @covers PHPSC\Conference\Domain\Entity\Event::isRegularRegistrationPeriod
     */
    public function isRegularRegistrationPeriodShouldReturnFalseWhenIsNotRegistrationPeriod()
    {
        $event = new Event();
        $event->setRegistrationInfo($this->registrationInfo);
        $event->setStartDate(new DateTime('2014-08-29'));
        $event->setEndDate(new DateTime('2014-08-30'));

        $this->registrationInfo->expects($this->any())
                               ->method('getStart')
                               ->willReturn(new DateTime('2014-05-05 00:00:00'));

        $this->registrationInfo->expects($this->any())
                               ->method('getEnd')
                               ->willReturn(new DateTime('2014-08-30 14:00:00'));

        $this->assertFalse($event->isRegularRegistrationPeriod(new DateTime('2014-04-29 10:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     */
    public function hasTalkSubmissionsShouldReturnFalseWhenStartDateIsNull()
    {
        $event = new Event();

        $this->assertFalse($event->hasTalkSubmissions());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     */
    public function hasTalkSubmissionsShouldReturnTrueWhenStartDateIsntNull()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime());

        $this->assertTrue($event->hasTalkSubmissions());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::isSubmissionsPeriod
     */
    public function isSubmissionsPeriodShouldReturnFalseWhenEventHasntTalkSubmission()
    {
        $event = new Event();

        $this->assertFalse($event->isSubmissionsPeriod(new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::isSubmissionsPeriod
     */
    public function isSubmissionsPeriodShouldReturnFalseWhenGivenDateIsBeforeSubmissionPeriod()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertFalse($event->isSubmissionsPeriod(new DateTime('2014-03-01 00:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::isSubmissionsPeriod
     */
    public function isSubmissionsPeriodShouldReturnFalseWhenGivenDateIsAfterSubmissionPeriod()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertFalse($event->isSubmissionsPeriod(new DateTime('2014-06-01 00:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::isSubmissionsPeriod
     */
    public function isSubmissionsPeriodShouldReturnTrueWhenGivenDateIsBetweenSubmissionPeriod()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertTrue($event->isSubmissionsPeriod(new DateTime('2014-05-01 00:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationStart
     */
    public function getTalkEvaluationStartShouldBeNullWhenEventHasntTalkSubmission()
    {
        $event = new Event();

        $this->assertNull($event->getTalkEvaluationStart());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationStart
     */
    public function getTalkEvaluationStartShouldBeASecondAfterTheSubmissionEnd()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertEquals(new DateTime('2014-06-01 00:00:00'), $event->getTalkEvaluationStart());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationEnd
     */
    public function getTalkEvaluationEndShouldBeNullWhenEventHasntTalkSubmission()
    {
        $event = new Event();

        $this->assertNull($event->getTalkEvaluationEnd());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationEnd
     */
    public function getTalkEvaluationEndShouldBeSevenDaysAfterSubmissionEnd()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertEquals(new DateTime('2014-06-07 23:59:59'), $event->getTalkEvaluationEnd());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::isSpeakerPromotionalPeriod
     */
    public function isSpeakerPromotionalPeriodShouldReturnFalseWhenEventHasntTalkSubmission()
    {
        $event = new Event();

        $this->assertFalse($event->isSpeakerPromotionalPeriod(new DateTime()));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::isSpeakerPromotionalPeriod
     */
    public function isSpeakerPromotionalPeriodShouldReturnFalseWhenGivenDateIsBeforeTalkEvaluationPeriod()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertFalse($event->isSpeakerPromotionalPeriod(new DateTime('2014-05-31 23:59:59')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::isSpeakerPromotionalPeriod
     */
    public function isSpeakerPromotionalPeriodShouldReturnFalseWhenGivenDateIsBetweenTalkEvaluationPeriod()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertFalse($event->isSpeakerPromotionalPeriod(new DateTime('2014-06-01 00:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::isSpeakerPromotionalPeriod
     */
    public function isSpeakerPromotionalPeriodShouldReturnFalseWhenGivenDateIsAfterAWeekFromTalkEvaluationPeriod()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertFalse($event->isSpeakerPromotionalPeriod(new DateTime('2014-06-15 00:00:00')));
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Event::__construct
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionStart
     * @covers PHPSC\Conference\Domain\Entity\Event::getSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::setSubmissionEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::hasTalkSubmissions
     * @covers PHPSC\Conference\Domain\Entity\Event::getTalkEvaluationEnd
     * @covers PHPSC\Conference\Domain\Entity\Event::isSpeakerPromotionalPeriod
     */
    public function isSpeakerPromotionalPeriodShouldReturnTrueWhenGivenDateIsBetweenEvaluationEndAndAWeekAfterIt()
    {
        $event = new Event();
        $event->setSubmissionStart(new DateTime('2014-04-01 00:00:00'));
        $event->setSubmissionEnd(new DateTime('2014-05-31 23:59:59'));

        $this->assertTrue($event->isSpeakerPromotionalPeriod(new DateTime('2014-06-14 23:59:59')));
    }
}
