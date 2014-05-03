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
     * @Column(type="decimal", precision=13, scale=2, nullable=false, name="regular_price")
     *
     * @var float
     */
    private $regularPrice;

    /**
     * @Column(type="decimal", precision=13, scale=2, name="early_price", nullable=true)
     *
     * @var float
     */
    private $earlyPrice;

    /**
     * @Column(type="decimal", precision=13, scale=2, name="late_price", nullable=true)
     *
     * @var float
     */
    private $latePrice;

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
            throw new InvalidArgumentException(
                'O id deve ser maior ou igual à ZERO'
            );
        }

        $this->id = (int) $id;
    }

    /**
     * @return \PHPSC\Conference\Domain\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param \PHPSC\Conference\Domain\Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(DateTime $start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd(DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return number
     */
    public function getRegularPrice()
    {
        return $this->regularPrice;
    }

    /**
     * @param number $regularPrice
     */
    public function setRegularPrice($regularPrice)
    {
        $this->regularPrice = (float) $regularPrice;
    }

    /**
     * @return number
     */
    public function getEarlyPrice()
    {
        return $this->earlyPrice;
    }

    /**
     * @param number $earlyPrice
     */
    public function setEarlyPrice($earlyPrice)
    {
        if ($earlyPrice !== null) {
            $this->earlyPrice = (float) $earlyPrice;
        }
    }

    /**
     * @return boolean
     */
    public function hasEarlyPrice()
    {
        return $this->getEarlyPrice() !== null;
    }

    /**
     * @return number
     */
    public function getLatePrice()
    {
        return $this->latePrice;
    }

    /**
     * @param number $latePrice
     */
    public function setLatePrice($latePrice)
    {
        if ($latePrice !== null) {
            $this->latePrice = (float) $latePrice;
        }
    }

    /**
     * @return boolean
     */
    public function hasLatePrice()
    {
        return $this->getLatePrice() !== null;
    }

    /**
     * @return number
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
