<?php
namespace PHPSC\Test\PHPSC\Conference\Domain\Entity;

use \DateTime;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\TalkType;

class TalkTest extends \PHPUnit_Framework_TestCase
{
    protected $creationTime;
    protected $event;
    protected $talk;
    protected $type;

    public function setUp()
    {
        $this->creationTime = $this->getMock('DateTime', array('getDateTime'));
        $this->event = $this->getMock('PHPSC\Conference\Domain\Entity\Event');
        $this->talk = new Talk();
        $this->type = $this->getMock('PHPSC\Conference\Domain\Entity\TalkType');
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
     * @covers PHPSC\Conference\Domain\Entity\Talk::getTitle
     * @covers PHPSC\Conference\Domain\Entity\Talk::setTitle
     */
    public function setTitleSuccessfully()
    {
        $this->assertAttributeEmpty('title', $this->talk);
        $this->assertEmpty($this->talk->getTitle());

        $this->talk->setTitle('Novo título');
        $this->assertAttributeEquals('Novo título', 'title', $this->talk);
        $this->assertEquals('Novo título', $this->talk->getTitle());
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

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getType
     * @covers PHPSC\Conference\Domain\Entity\Talk::setType
     */
    public function setTypeSuccessfully()
    {
        $this->assertAttributeEmpty('type', $this->talk);
        $this->assertNull($this->talk->getType());

        $this->talk->setType($this->type);
        $this->assertAttributeInstanceOf('PHPSC\Conference\Domain\Entity\TalkType', 'type', $this->talk);
        $this->assertInstanceOf('PHPSC\Conference\Domain\Entity\TalkType', $this->talk->getType());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getTags
     * @covers PHPSC\Conference\Domain\Entity\Talk::setTags
     */
    public function setTagsSuccessfully()
    {
        $this->assertAttributeEmpty('tags', $this->talk);
        $this->assertEmpty($this->talk->getTags());

        $this->talk->setTags(array('tag1', 'tag2'));
        $this->assertCount(2, $this->talk->getTags());
        $this->assertEquals(array('tag1', 'tag2'), $this->talk->getTags());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage As tags não podem ser vazias
     */
    public function setTagsWithEmptyArrayMustThrowAnException()
    {
        $this->talk->setTags(array());
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getShortDescription
     * @covers PHPSC\Conference\Domain\Entity\Talk::setShortDescription
     */
    public function setShortDescriptionSuccessfully()
    {
        $this->assertAttributeEmpty('shortDescription', $this->talk);
        $this->assertEmpty($this->talk->getShortDescription());

        $this->talk->setShortDescription('Resumo');
        $this->assertAttributeEquals('Resumo', 'shortDescription', $this->talk);
        $this->assertEquals('Resumo', $this->talk->getShortDescription());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O resumo não pode ser vazio
     */
    public function setShortDescriptionWithEmptyValueMustThrowAnException()
    {
        $this->talk->setShortDescription('');
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getLongDescription
     * @covers PHPSC\Conference\Domain\Entity\Talk::setLongDescription
     */
    public function setLongDescriptionSuccessfully()
    {
        $this->assertAttributeEmpty('longDescription', $this->talk);
        $this->assertEmpty($this->talk->getLongDescription());

        $this->talk->setLongDescription('Descrição');
        $this->assertAttributeEquals('Descrição', 'longDescription', $this->talk);
        $this->assertEquals('Descrição', $this->talk->getLongDescription());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage A descrição não pode ser vazia
     */
    public function setLongDescriptionWithEmptyValueMustThrowAnException()
    {
        $this->talk->setLongDescription('');
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getCost
     * @covers PHPSC\Conference\Domain\Entity\Talk::setCost
     */
    public function setCostSuccessfully()
    {
        $this->assertAttributeEmpty('cost', $this->talk);
        $this->assertEmpty($this->talk->getCost());

        $this->talk->setCost(25.00);
        $this->assertAttributeEquals(25.00, 'cost', $this->talk);
        $this->assertEquals(25.00, $this->talk->getCost());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O custo da palestra deve ser maior que ZERO
     */
    public function setCostWithZeroAsValueMustThrowAnException()
    {
        $this->talk->setCost(0);
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getApproved
     * @covers PHPSC\Conference\Domain\Entity\Talk::setApproved
     */
    public function setApprovedSuccessfully()
    {
        $this->assertAttributeEmpty('approved', $this->talk);
        $this->assertEmpty($this->talk->getApproved());

        $this->talk->setApproved(true);
        $this->assertAttributeEquals(true, 'approved', $this->talk);
        $this->assertTrue($this->talk->getApproved());

        $this->talk->setApproved(false);
        $this->assertAttributeEquals(false, 'approved', $this->talk);
        $this->assertFalse($this->talk->getApproved());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Aprovado deve ser TRUE ou FALSE
     */
    public function setApprovedWithInvalidValueMustThrowAnException()
    {
        $this->talk->setApproved('');
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getId
     * @covers PHPSC\Conference\Domain\Entity\Talk::setId
     */
    public function setIdSuccessfully()
    {
        $this->assertAttributeEmpty('id', $this->talk);
        $this->assertEmpty($this->talk->getId());

        $this->talk->setId(18);
        $this->assertAttributeEquals(18, 'id', $this->talk);
        $this->assertEquals(18, $this->talk->getId());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage O id deve ser maior ou igual à ZERO
     */
    public function setIdWithZeroAsValueMustThrowAnException()
    {
        $this->talk->setId(0);
    }

    /**
     * @test
     * @covers PHPSC\Conference\Domain\Entity\Talk::getCreationTime
     * @covers PHPSC\Conference\Domain\Entity\Talk::setCreationTime
     */
    public function setCreationTimeSuccessfully()
    {
        $this->assertAttributeEmpty('creationTime', $this->talk);
        $this->assertEmpty($this->talk->getCreationTime());

        $this->talk->setCreationTime($this->creationTime);
        $this->assertAttributeEquals($this->creationTime, 'creationTime', $this->talk);
        $this->assertEquals($this->creationTime, $this->talk->getCreationTime());
    }
}