<?php
namespace PHPSC\Conference\Domain\Entity;

use \PHPSC\Conference\Infra\Persistence\Entity;
use \InvalidArgumentException;
use \DateTime;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\AttendeeRepository")
 * @Table("attendee")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Attendee implements Entity
{
    /**
     * @var string
     */
    const PAYMENT_NOT_NECESSARY = '0';

    /**
     * @var string
     */
    const CHECK_PAYMENT = '1';

    /**
     * @var string
     */
    const WAITING_PAYMENT = '2';

    /**
     * @var string
     */
    const APPROVED = '3';

    /**
     * @var string
     */
    const CANCELLED = '4';

    /**
     * @Id
 	 * @Column(type="integer")
	 * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Event", cascade={"all"})
	 * @JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    private $event;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"})
	 * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var \PHPSC\Conference\Domain\Entity\User
     */
    private $user;

    /**
     * @Column(type="decimal", nullable=false)
     * @var float
     */
    private $cost;

    /**
     * @Column(type="string", nullable=false)
     * @var string
     */
    private $status;

    /**
     * @Column(type="datetime", name="creation_time", nullable=false)
     * @var \DateTime
     */
    private $creationTime;

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
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

	/**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

	/**
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

	/**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

	/**
     * @return number
     */
    public function getCost()
    {
        return $this->cost;
    }

	/**
     * @param number $cost
     */
    public function setCost($cost)
    {
        $this->cost = (float) $cost;
    }

	/**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

	/**
     * @param string $status
     */
    public function setStatus($status)
    {
        $possible = array(
            static::PAYMENT_NOT_NECESSARY,
            static::CHECK_PAYMENT,
            static::WAITING_PAYMENT,
            static::APPROVED,
            static::CANCELLED
        );

        if (!in_array($status, $possible, true)) {
            throw new InvalidArgumentException('Status de inscrição inválido');
        }

        $this->status = (string) $status;
    }

    /**
     * @return boolean
     */
    public function isApproved()
    {
        return $this->getStatus() == static::APPROVED;
    }

    /**
     * @return boolean
     */
    public function isCancelled()
    {
        return $this->getStatus() == static::CANCELLED;
    }

    /**
     * @return boolean
     */
    public function isPaymentNotVerified()
    {
        return $this->getStatus() == static::CHECK_PAYMENT;
    }

    /**
     * @return boolean
     */
    public function isWaitingForPayment()
    {
        return $this->getStatus() == static::WAITING_PAYMENT;
    }

    /**
     * @return boolean
     */
    public function isPaymentNotNecessary()
    {
        return $this->getStatus() == static::PAYMENT_NOT_NECESSARY;
    }

    /**
     * @return boolean
     */
    public function isPaymentRequired()
    {
        return $this->isWaitingForPayment() && $this->getCost() > 0;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return !$this->isCancelled();
    }

    /**
     * Approves the attendee registration
     */
    public function approve()
    {
        if ($this->isPaymentNotVerified() || $this->isWaitingForPayment()) {
            $this->setStatus(static::APPROVED);
        }
    }

    /**
     * Cancels the attendee registration
     */
    public function cancel()
    {
        $this->setStatus(static::CANCELLED);
    }

	/**
     * @return \DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

	/**
     * @param \DateTime $creationTime
     */
    public function setCreationTime(DateTime $creationTime)
    {
        $this->creationTime = $creationTime;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param float $cost
     * @param string $status
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    protected static function create(Event $event, User $user, $cost, $status)
    {
        $attendee = new Attendee();

        $attendee->setEvent($event);
        $attendee->setUser($user);
        $attendee->setCost($cost);
        $attendee->setStatus($status);
        $attendee->setCreationTime(new DateTime());

        return $attendee;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param float $cost
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public static function createRegularAttendee(
        Event $event,
        User $user,
        $cost
    ) {
        return static::create($event, $user, $cost, static::WAITING_PAYMENT);
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public static function createSpeakerAttendee(Event $event, User $user)
    {
        return static::create($event, $user, 0, static::PAYMENT_NOT_NECESSARY);
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public static function createStudentAttendee(Event $event, User $user)
    {
        return static::create($event, $user, 0, static::CHECK_PAYMENT);
    }
}