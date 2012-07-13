<?php
namespace PHPSC\Conference\Domain\Repository;

use \PHPSC\Conference\Infra\Persistence\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param string $twitterUser
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function findOneByTwitterUser($twitterUser)
    {
        $query = $this->createQueryBuilder('user')
                      ->andWhere('user.twitterUser = ?1')
                      ->setParameter(1, $twitterUser)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        $results = $query->getResult();

        return isset($results[0]) ? $results[0] : null;
    }
}