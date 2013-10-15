<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Payment;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

class PaymentRepository extends EntityRepository
{
    /**
     * @param string $code
     * @return Payment
     */
    public function findOneByCode($code)
    {
        $query = $this->createQueryBuilder('payment')
                      ->andWhere('payment.code = ?1')
                      ->setParameter(1, $code)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @return Payment
     */
    public function findOneById($id)
    {
        $query = $this->createQueryBuilder('payment')
                      ->andWhere('payment.id = ?1')
                      ->setParameter(1, $id)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }
}
