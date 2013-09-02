<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Infra\Persistence\EntityRepository;
use PHPSC\Conference\Domain\Entity\SocialProfile;

class UserRepository extends EntityRepository
{
    /**
     * @param string $email
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function findOneByEmail($email)
    {
        $query = $this->createQueryBuilder('user')
                      ->andWhere('user.email = ?1')
                      ->setParameter(1, $email)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }
}
