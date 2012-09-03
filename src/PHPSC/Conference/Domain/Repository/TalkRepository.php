<?php
namespace PHPSC\Conference\Domain\Repository;

use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\User;
use \PHPSC\Conference\Infra\Persistence\EntityRepository;

class TalkRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return \PHPSC\Conference\Domain\Entity\Talk
     */
    public function findById($id, User $user = null)
    {
        $query = $this->createQueryBuilder('talk')
                      ->andWhere('talk.id = ?1')
                      ->setParameter(1, $id)
                      ->setMaxResults(1);

        if ($user !== null) {
            $query->andWhere('?2 MEMBER OF talk.speakers')
                  ->setParameter(2, $user);
        }

        $query = $query->getQuery();
        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param boolean $approvedOnly
     * @return \PHPSC\Conference\Domain\Entity\Talk[]
     */
    public function findByUserAndEvent(User $user, Event $event, $approvedOnly)
    {
        $query = $this->createQueryBuilder('talk')
                      ->andWhere('talk.event = ?1')
                      ->andWhere('?2 MEMBER OF talk.speakers')
                      ->setParameter(1, $event)
                      ->setParameter(2, $user)
                      ->orderBy('talk.title');

        if ($approvedOnly) {
            $query->andWhere('talk.approved = 1');
        }

        $query = $query->getQuery();
        $query->useQueryCache(true);

        return $query->getResult();
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param boolean $approvedOnly
     * @return \PHPSC\Conference\Domain\Entity\Talk[]
     */
    public function findByEvent(Event $event, $approvedOnly)
    {
        $query = $this->createQueryBuilder('talk')
                      ->andWhere('talk.event = ?1')
                      ->setParameter(1, $event)
                      ->orderBy('talk.title');

        if ($approvedOnly) {
            $query->andWhere('talk.approved = 1');
        }

        $query = $query->getQuery();
        $query->useQueryCache(true);

        return $query->getResult();
    }
}