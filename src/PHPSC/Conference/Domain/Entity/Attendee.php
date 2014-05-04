<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use PHPSC\Conference\Infra\Persistence\Entity;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\AttendeeRepository")
 * @Table("attendee", indexes={@Index(name="attendee_index0", columns={"status"})})
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
     * @deprecated
     * @var string
     */
    const CANCELLED = '4';

    /**
     * @var string
     */
    const TALKS_ONLY = '0';

    /**
     * @var string
     */
    const WORKSHOPS_AND_TALKS = '1';

    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Event", cascade={"all"})
     * @JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     *
     * @var Event
     */
    private $event;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"})
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @var User
     */
    private $user;

    /**
     * @Column(type="string", columnDefinition="ENUM('0', '1', '2', '3', '4') NOT NULL")
     *
     * @var string
     */
    private $status;

    /**
     * @Column(type="boolean", options={"default" = 0}, nullable=false)
     *
     * @var boolean
     */
    private $arrived;

    /**
     * @Column(type="string", name="registration_type", length=1, options={"default" = "0"}, nullable=false)
     *
     * @var string
     */
    private $registrationType;

    /**
     * @Column(type="boolean", name="student_registration", options={"default" = 0}, nullable=false)
     *
     * @var boolean
     */
    private $studentRegistration;

    /**
     * @ManyToOne(targetEntity="DiscountCoupon")
     * @JoinColumn(name="coupon_id", referencedColumnName="id")
     *
     * @var DiscountCoupon
     */
    private $discount;

    /**
     * @Column(type="datetime", name="creation_time", nullable=false)
     *
     * @var DateTime
     */
    private $creationTime;

    /**
     * @ManyToMany(targetEntity="Payment")
     * @JoinTable(
     *      name="attendee_payment",
     *      joinColumns={@JoinColumn(name="attendee_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="payment_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @var ArrayCollection
     **/
    private $payments;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->setPayments(new ArrayCollection());
    }

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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getRegistrationType()
    {
        return $this->registrationType;
    }

    /**
     * @param string $registrationType
     */
    public function setRegistrationType($registrationType)
    {
        $this->registrationType = $registrationType;
    }

    /**
     * @return boolean
     */
    public function canAttendTalksDayOnly()
    {
        return $this->getRegistrationType() === self::TALKS_ONLY;
    }

    /**
     * @return boolean
     */
    public function canAttendAllDays()
    {
        return $this->getRegistrationType() === self::WORKSHOPS_AND_TALKS;
    }

    /**
     * @return boolean
     */
    public function isStudentRegistration()
    {
        return $this->studentRegistration === true;
    }

    /**
     * @param boolean $studentRegistration
     */
    public function setStudentRegistration($studentRegistration)
    {
        $this->studentRegistration = $studentRegistration;
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
     * @return boolean
     */
    public function hasArrived()
    {
        return $this->arrived;
    }

    /**
     * @param boolean $arrived
     */
    public function setArrived($arrived)
    {
        $this->arrived = $arrived;
    }

    /**
     * @return DiscountCoupon
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param DiscountCoupon $discount
     */
    public function setDiscount(DiscountCoupon $discount = null)
    {
        $this->discount = $discount;
    }

    /**
     * @param DiscountCoupon $discount
     */
    public function applyDiscount(DiscountCoupon $discount)
    {
        $this->setDiscount($discount);

        if ($this->isWaitingForPayment() && !$discount->isParcialDiscount()) {
            $this->setStatus(static::APPROVED);
        }
    }

    /**
     * @return DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param DateTime $creationTime
     */
    public function setCreationTime(DateTime $creationTime)
    {
        $this->creationTime = $creationTime;
    }

    /**
     * @return ArrayCollection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param ArrayCollection $payments
     */
    public function setPayments(ArrayCollection $payments)
    {
        $this->payments = $payments;
    }

    /**
     * @param Payment $payment
     */
    public function addPayment(Payment $payment)
    {
        $this->getPayments()->add($payment);
    }

    /**
     * @return Payment
     */
    public function getLastPayment()
    {
        return $this->getPayments()->last();
    }

    /**
     * @param Event $event
     * @param User $user
     * @param string $status
     * @return Attendee
     */
    protected static function create(Event $event, User $user, $status)
    {
        $attendee = new Attendee();

        $attendee->setEvent($event);
        $attendee->setUser($user);
        $attendee->setStatus($status);
        $attendee->setArrived(false);
        $attendee->setCreationTime(new DateTime());

        return $attendee;
    }

    /**
     * @param Event $event
     * @param User $user
     * @return Attendee
     */
    public static function createRegularAttendee(Event $event, User $user)
    {
        return static::create($event, $user, static::WAITING_PAYMENT);
    }

    /**
     * @param Event $event
     * @param User $user
     * @return Attendee
     */
    public static function createSpeakerAttendee(Event $event, User $user)
    {
        return static::create($event, $user, static::PAYMENT_NOT_NECESSARY);
    }

    /**
     * @param Event $event
     * @param User $user
     * @return Attendee
     */
    public static function createStudentAttendee(Event $event, User $user)
    {
        return static::create($event, $user, static::CHECK_PAYMENT);
    }

    /**
     * @return string
     */
    public function getStatusDescription()
    {
        switch (true) {
            case $this->isPaymentNotNecessary():
                return 'Pagamento dispensado';
            case $this->isPaymentNotVerified():
                return 'Verificar pagamento';
            case $this->isWaitingForPayment():
                return 'Aguardando pagamento';
            case $this->isApproved():
                return 'Pagamento confirmado';
            case $this->isCancelled():
                return 'Inscrição cancelada';
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getUser()->getName(),
            'status' => $this->getStatus()
        );
    }
}
