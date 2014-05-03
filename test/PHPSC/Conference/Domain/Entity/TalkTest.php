<?php
namespace PHPSC\Test\PHPSC\Conference\Domain\Entity;

use DateTime;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\TalkType;
use Doctrine\Common\Collections\ArrayCollection;

class TalkTest extends \PHPUnit_Framework_TestCase
{
    protected $creationTime;
    protected $event;
    protected $talk;
    protected $type;
    protected $speakers;

    public function setUp()
    {
        $this->talk = new Talk();

        $this->creationTime = $this->getMock(DateTime::class, ['getDateTime']);
        $this->event = $this->getMock(Event::class, [], [], '', false);
        $this->type = $this->getMock(TalkType::class);
        $this->speakers = $this->getMock(ArrayCollection::class);
    }

    /**
     * @test
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
     */
    public function setEventSuccessfully()
    {
        $this->assertAttributeEmpty('event', $this->talk);
        $this->assertNull($this->talk->getEvent());

        $this->talk->setEvent($this->event);
        $this->assertAttributeSame($this->event, 'event', $this->talk);
        $this->assertSame($this->event, $this->talk->getEvent());
    }

    /**
     * @test
     */
    public function setTypeSuccessfully()
    {
        $this->assertAttributeEmpty('type', $this->talk);
        $this->assertNull($this->talk->getType());

        $this->talk->setType($this->type);
        $this->assertAttributeSame($this->type, 'type', $this->talk);
        $this->assertSame($this->type, $this->talk->getType());
    }

    /**
     * @test
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
     */
    public function setCreationTimeSuccessfully()
    {
        $this->assertAttributeEmpty('creationTime', $this->talk);
        $this->assertEmpty($this->talk->getCreationTime());

        $this->talk->setCreationTime($this->creationTime);
        $this->assertAttributeSame($this->creationTime, 'creationTime', $this->talk);
        $this->assertSame($this->creationTime, $this->talk->getCreationTime());
    }

    /**
     * @test
     */
    public function setSpeakers()
    {
        $this->assertAttributeEmpty('speakers', $this->talk);
        $this->assertInstanceOf(ArrayCollection::class, $this->talk->getSpeakers());
        $this->assertEmpty($this->talk->getSpeakers());

        $this->talk->setSpeakers($this->speakers);
        $this->assertAttributeSame($this->speakers, 'speakers', $this->talk);
        $this->assertSame($this->speakers, $this->talk->getSpeakers());
    }
}