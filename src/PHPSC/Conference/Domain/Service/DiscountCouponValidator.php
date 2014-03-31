<?php
namespace PHPSC\Conference\Domain\Service;

use InvalidArgumentException;
use PHPSC\Conference\Domain\Repository\DiscountCouponRepository;
use PHPSC\Conference\Domain\Entity\DiscountCoupon;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class DiscountCouponValidator
{
    /**
     * @var DiscountCouponRepository
     */
    protected $repository;

    /**
     * @param DiscountCouponRepository $repository
     */
    public function __construct(DiscountCouponRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $code
     * @return DiscountCoupon
     * @throws InvalidArgumentException
     */
    public function validate($code)
    {
        $coupon = $this->repository->findOneByCode($code);

        if ($coupon === null) {
            throw new InvalidArgumentException('Cupom inválido!');
        }

        if ($coupon->getUsageLimit() == $this->repository->getUsageCount($coupon)) {
            throw new InvalidArgumentException('Este cupom não pode mais ser utilizado!');
        }

        return $coupon;
    }
}
