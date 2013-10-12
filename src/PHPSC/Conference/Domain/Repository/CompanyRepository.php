<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Company;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

class CompanyRepository extends EntityRepository
{
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
