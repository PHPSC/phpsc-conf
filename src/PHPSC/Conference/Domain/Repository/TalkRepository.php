<?php
namespace PHPSC\Conference\Domain\Repository;

use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\User;
use \PHPSC\Conference\Infra\Persistence\EntityRepository;

class TalkRepository extends EntityRepository
{
    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @return \PHPSC\Conference\Domain\Entity\Talk[]
     */
    public function findByUserAndEvent(User $user, Event $event)
    {
        $query = $this->createQueryBuilder('talk')
                      ->andWhere('talk.event = ?1')
                      ->andWhere('?2 MEMBER OF talk.speakers')
                      ->setParameter(1, $event)
                      ->setParameter(2, $user)
                      ->orderBy('talk.title')
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getResult();
    }
}