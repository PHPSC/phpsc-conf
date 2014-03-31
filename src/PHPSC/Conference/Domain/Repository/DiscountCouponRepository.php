<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Infra\Persistence\EntityRepository;
use PHPSC\Conference\Domain\Entity\DiscountCoupon;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class DiscountCouponRepository extends EntityRepository
{
    /**
     * @param string $code
     * @return DiscountCoupon
     */
    public function findOneByCode($code)
    {
        $query = $this->createQueryBuilder('coupon')
                      ->andWhere('coupon.code = ?1')
                      ->setParameter(1, $code)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    /**
     * @param DiscountCoupon $coupon
     * @return int
     */
    public function getUsageCount(DiscountCoupon $coupon)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(attendee.id) FROM PHPSC\Conference\Domain\Entity\Attendee attendee
            WHERE attendee.discount = ?1'
        );

        $query->setParameter(1, $coupon);
        $query->useQueryCache(true);

        return $query->getSingleScalarResult();
    }
}
