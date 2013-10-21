<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

class OpinionRepository extends EntityRepository
{
    /**
     * @param Event $event
     * @param User $user
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

    /**
     * @param Talk $talk
     * @return array<\PHPSC\Conference\Domain\Entity\Opinion>
     */
    public function findByTalk(Talk $talk)
    {
        $query = $this->createQueryBuilder('evaluation')
                      ->andWhere('evaluation.talk = ?1')
                      ->setParameter(1, $talk)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getResult();
    }
}
