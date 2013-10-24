<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use InvalidArgumentException;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\DiscountCouponRepository")
 * @Table("discount_coupon")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class DiscountCoupon
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", length=80, unique=true)
     * @var string
     */
    private $code;

    /**
     * @Column(type="decimal", precision=13, scale=2)
     * @var float
     */
    private $discount;

    /**
     * @Column(type="integer", name="usage_limit")
     * @var int
     */
    private $usageLimit;

    /**
     * @Column(type="datetime", name="creation_time")
     * @var DateTime
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
        $this->id = $id;
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
     * @return number
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param number $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return boolean
     */
    public function isParcialDiscount()
    {
        return $this->getDiscount() < 100;
    }

    /**
     * @param float $cost
     * @return float
     */
    public function applyDiscountTo($cost)
    {
        return $cost - ($cost * $this->getDiscount() / 100);
    }

    /**
     * @return number
     */
    public function getUsageLimit()
    {
        return $this->usageLimit;
    }

    /**
     * @param number $usageLimit
     */
    public function setUsageLimit($usageLimit)
    {
        $this->usageLimit = $usageLimit;
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
}
