<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Company;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

class CompanyRepository extends EntityRepository
{
    /**
     * @param string $name
     * @param string $socialId
     * @return array
     */
    public function search($name, $socialId)
    {
        $builder = $this->createQueryBuilder('company');

        if ($name !== null) {
            $builder->andWhere('company.name LIKE ?1')
                    ->setParameter(1, '%' . $name . '%');
        }

        if ($socialId !== null) {
            $builder->andWhere('company.socialId = ?2')
                    ->setParameter(2, $socialId);
        }

        $query = $builder->getQuery();
        $query->useQueryCache(true);

        return $query->getResult();
    }

    /**
     * @param string $socialId
     * @return Company
     */
    public function findOneBySocialId($socialId)
    {
        $query = $this->createQueryBuilder('company')
                      ->andWhere('company.socialId = ?1')
                      ->setParameter(1, $socialId)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }
}
