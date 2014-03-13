<?php
namespace PHPSC\Test\PHPSC\Conference\Domain\Entity;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Talk;

class TalkTest extends \PHPUnit_Framework_TestCase
{
    protected $event;
    protected $talk;

    public function setUp()
    {
        $this->event = $this->getMock('PHPSC\Conference\Domain\Entity\Event');
        $this->talk = new Talk();
    }

    public function tearDown()
    {
        $this->event = null;
        $this->talk = null;
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getComplexity
     * @covers PHPSC\Conference\Domain\Entity\Talk::setComplexity
     */
    public function setComplexitySuccessfully()
    {
        $this->assertAttributeEmpty('complexity', $this->talk);
        $this->assertNull($this->talk->getComplexity());

        $this->talk->setComplexity(Talk::LOW_COMPLEXITY);
        $this->assertAttributeEquals('L', 'complexity', $this->talk);
        $this->assertEquals('L', $this->talk->getComplexity());

        $this->talk->setComplexity(Talk::MEDIUM_COMPLEXITY);
        $this->assertAttributeEquals('M', 'complexity', $this->talk);
        $this->assertEquals('M', $this->talk->getComplexity());

        $this->talk->setComplexity(Talk::HIGH_COMPLEXITY);
        $this->assertAttributeEquals('H', 'complexity', $this->talk);
        $this->assertEquals('H', $this->talk->getComplexity());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O valor inválido para nível
     */
    public function setComplexityWithEmptyValueMustThrowAnException()
    {
        $this->talk->setComplexity('');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O valor inválido para nível
     */
    public function setComplexityWithNullValueMustThrowAnException()
    {
        $this->talk->setComplexity(null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O valor inválido para nível
     */
    public function setComplexityWithUnknownValueMustThrowAnException()
    {
        $this->talk->setComplexity('X');
    }

    /**
     * @test
     */
    public function setTitleSuccessfully()
    {
        $this->assertAttributeEmpty('title', $this->talk);

        $this->talk->setTitle('Novo título');
        $this->assertAttributeEquals('Novo título', 'title', $this->talk);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O título não pode ser vazio
     */
    public function setTitleWithEmptyValueMustThrowAnException()
    {
        $this->talk->setTitle('');
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getEvent
     * @covers PHPSC\Conference\Domain\Entity\Talk::setEvent
     */
    public function setEventSuccessfully()
    {
        $this->assertAttributeEmpty('event', $this->talk);
        $this->assertNull($this->talk->getEvent());

        $this->talk->setEvent($this->event);
        $this->assertAttributeInstanceOf('PHPSC\Conference\Domain\Entity\Event', 'event', $this->talk);
        $this->assertInstanceOf('PHPSC\Conference\Domain\Entity\Event', $this->talk->getEvent());
    }
}