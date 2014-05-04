<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use InvalidArgumentException;
use PHPSC\Conference\Infra\Persistence\Entity;

/**
 * @Entity
 * @Table("registration_info")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class RegistrationInfo implements Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;

    /**
     * @OneToOne(targetEntity="Event", inversedBy="registrationInfo")
     * @JoinColumn(name="event_id", referencedColumnName="id", unique=true, nullable=false)
     *
     * @var Event
     */
    private $event;

    /**
     * @Column(type="datetime", nullable=false)
     *
     * @var DateTime
     */
    private $start;

    /**
     * @Column(type="datetime", nullable=false)
     *
     * @var DateTime
     */
    private $end;

    /**
     * @Column(type="decimal", precision=13, scale=2, nullable=false, name="workshops_price")
     *
     * @var float
     */
    private $workshopsPrice;

    /**
     * @Column(type="decimal", precision=13, scale=2, nullable=false, name="talks_price")
     *
     * @var float
     */
    private $talksPrice;

    /**
     * @Column(type="decimal", precision=13, scale=2, name="cost_variation", nullable=false)
     *
     * @var float
     */
    private $costVariation;

    /**
     * @Column(type="decimal", precision=13, scale=2, name="student_discount", nullable=true)
     *
     * @var float
     */
    private $studentDiscount;

    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('O id deve ser maior ou igual à ZERO');
        }

        $this->id = (int) $id;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param DateTime $start
     */
    public function setStart(DateTime $start)
    {
        $this->start = $start;
    }

    /**
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param DateTime $end
     */
    public function setEnd(DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return float
     */
    public function getWorkshopsPrice()
    {
        return $this->workshopsPrice;
    }

    /**
     * @param float $workshopsPrice
     */
    public function setWorkshopsPrice($workshopsPrice)
    {
        $this->workshopsPrice = $workshopsPrice;
    }

    /**
     * @return float
     */
    public function getTalksPrice()
    {
        return $this->talksPrice;
    }

    /**
     * @param float $talksPrice
     */
    public function setTalksPrice($talksPrice)
    {
        $this->talksPrice = $talksPrice;
    }

    /**
     * @return float
     */
    public function getCostVariation()
    {
        return $this->costVariation;
    }

    /**
     * @param float $costVariation
     */
    public function setCostVariation($costVariation)
    {
        $this->costVariation = $costVariation;
    }

    /**
     * @return float
     */
    public function getStudentDiscount()
    {
        return $this->studentDiscount;
    }

    /**
     * @param float $studentDiscount
     */
    public function setStudentDiscount($studentDiscount)
    {
        $this->studentDiscount = $studentDiscount;
    }

    /**
     * @return boolean
     */
    public function hasStudentDiscount()
    {
        return $this->getStudentDiscount() !== null;
    }
}
