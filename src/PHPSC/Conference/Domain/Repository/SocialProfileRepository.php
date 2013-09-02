<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\SocialProfile;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

class SocialProfileRepository extends EntityRepository
{
    /**
     * @param string $provider
     * @param string $socialId
     * @return SocialProfile
     */
    public function findOneBySocialId($provider, $socialId)
    {
        $query = $this->createQueryBuilder('profile')
                      ->andWhere('profile.provider = ?1')
                      ->andWhere('profile.socialId = ?2')
                      ->setParameter(1, $provider)
                      ->setParameter(2, $socialId)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }
}
