<?php
namespace PHPSC\Test\PHPSC\Conference\Domain\Entity;

use PHPSC\Conference\Domain\Entity\Talk;

class TalkTest extends \PHPUnit_Framework_TestCase
{
    protected $talk;

    public function setUp()
    {
        $this->talk = new Talk();
    }

    public function tearDown()
    {
        $this->talk = null;
    }

    /**
     * @test
     */
    public function setComplexitySuccessfully()
    {
        $this->assertAttributeEmpty('complexity', $this->talk);

        $this->talk->setComplexity(Talk::LOW_COMPLEXITY);
        $this->assertAttributeEquals('L', 'complexity', $this->talk);

        $this->talk->setComplexity(Talk::MEDIUM_COMPLEXITY);
        $this->assertAttributeEquals('M', 'complexity', $this->talk);

        $this->talk->setComplexity(Talk::HIGH_COMPLEXITY);
        $this->assertAttributeEquals('H', 'complexity', $this->talk);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O valor inválido para nível
     */
    public function setComplexityMustThrowAnExceptionWithEmptyValue()
    {
        $this->talk->setComplexity('');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O valor inválido para nível
     */
    public function setComplexityMustThrowAnExceptionWithNullValue()
    {
        $this->talk->setComplexity(null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O valor inválido para nível
     */
    public function setComplexityMustThrowAnExceptionWithUnknownValue()
    {
        $this->talk->setComplexity('X');
    }
}