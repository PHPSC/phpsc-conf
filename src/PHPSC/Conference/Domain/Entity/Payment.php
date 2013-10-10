<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use InvalidArgumentException;
use PHPSC\Conference\Infra\Persistence\Entity;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\PaymentRepository")
 * @Table("payment", indexes={@Index(name="payment_index0", columns={"status"})})
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Payment implements Entity
{
    /**
     * @var string
     */
    const WAITING_PAYMENT = '0';

    /**
     * @var string
     */
    const APPROVED = '1';

    /**
     * @var string
     */
    const CANCELLED = '2';

    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", nullable=true, unique=true)
     * @var string
     */
    private $code;

    /**
     * @Column(type="decimal", precision=13, scale=2)
     * @var float
     */
    private $cost;

    /**
     * @Column(type="string", columnDefinition="ENUM('0', '1', '2') NOT NULL")
     * @var string
     */
    private $status;

    /**
     * @Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @Column(type="datetime", name="creation_time")
     * @var string
     */
    private $creationTime;

    /**
     * @Column(type="datetime", name="last_update_time", nullable=true)
     * @var string
     */
    private $lastUpdateTime;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                'O id deve ser maior que ZERO'
            );
        }

        $this->id = (int) $id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost($cost)
    {
        if ($cost <= 0) {
            throw new InvalidArgumentException(
                'O custo do pagamento deve ser maior que ZERO'
            );
        }

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
    public function isWaitingForPayment()
    {
        return $this->getStatus() == static::WAITING_PAYMENT;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
    }

    /**
     * @return DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param DateTime $lastUpdateTime
     */
    public function setCreationTime(DateTime $creationTime)
    {
        $this->creationTime = $creationTime;
    }

    /**
     * @return DateTime
     */
    public function getLastUpdateTime()
    {
        return $this->lastUpdateTime;
    }

    /**
     * @param DateTime $lastUpdateTime
     */
    public function setLastUpdateTime(DateTime $lastUpdateTime = null)
    {
        $this->lastUpdateTime = $lastUpdateTime;
    }

    /**
     * Approve the payment
     */
    public function approve()
    {
        if (!$this->isWaitingForPayment()) {
            return ;
        }

        $this->setStatus(static::APPROVED);
        $this->setLastUpdateTime(new DateTime());
    }

    /**
     * Cancel the payment
     */
    public function cancel()
    {
        if (!$this->isWaitingForPayment()) {
            return ;
        }

        $this->setStatus(static::CANCELLED);
        $this->setLastUpdateTime(new DateTime());
    }

    /**
     * @param float $cost
     * @param string $description
     * @return Payment
     */
    public static function create($cost, $description)
    {
        $payment = new static();
        $payment->setCost($cost);
        $payment->setDescription($description);
        $payment->setCreationTime(new DateTime());
        $payment->setStatus(static::WAITING_PAYMENT);

        return $payment;
    }
}
