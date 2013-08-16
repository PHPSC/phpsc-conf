<?php
namespace PHPSC\Conference\Domain\Repository;

use \PHPSC\Conference\Infra\Persistence\EntityRepository;
use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\User;

class OpinionRepository extends EntityRepository
{
    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return int
     */
    public function findNumberOfLikesFor(Event $event, User $user)
    {
        $query = $this->createQueryBuilder('opinion')
                      ->select('COUNT(opinion.id)')
                      ->leftJoin('opinion.talk', 'talk')
                      ->andWhere('talk.event = ?1')
                      ->andWhere('opinion.user = ?2')
                      ->andWhere('opinion.likes = 1')
                      ->setParameter(1, $event)
                      ->setParameter(2, $user)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getSingleScalarResult();
    }
}
