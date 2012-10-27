<?php
namespace PHPSC\Conference\Domain\Repository;

use \PHPSC\Conference\Infra\Persistence\EntityRepository;
use \PHPSC\Conference\Domain\Entity\User;
use \PHPSC\Conference\Domain\Entity\Event;

class AttendeeRepository extends EntityRepository
{
    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @return multitype:\PHPSC\Conference\Domain\Entity\Attendee
     */
    public function findByEventAndUser(Event $event, User $user)
    {
        $query = $this->createQueryBuilder('attendee')
                      ->andWhere('attendee.event = ?1')
                      ->andWhere('attendee.user = ?2')
                      ->setParameter(1, $event)
                      ->setParameter(2, $user)
                      ->orderBy('attendee.id', 'DESC')
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getResult();
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @return number
     */
    public function getCountOfActiveAttendee(Event $event)
    {
        $query = $this->createQueryBuilder('attendee')
                      ->select('COUNT(attendee.id)')
                      ->andWhere('attendee.event = ?1')
                      ->andWhere('attendee.status NOT IN (?2, ?3)')
                      ->setParameter(1, $event)
                      ->setParameter(2, '2')
                      ->setParameter(3, '4')
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getSingleScalarResult();
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @return number
     */
    public function getCountOfArrivedAttendee(Event $event)
    {
        $query = $this->createQueryBuilder('attendee')
                      ->select('COUNT(attendee.id)')
                      ->andWhere('attendee.event = ?1')
                      ->andWhere('attendee.arrived = ?2')
                      ->andWhere('attendee.status NOT IN (?3, ?4)')
                      ->setParameter(1, $event)
                      ->setParameter(2, true)
                      ->setParameter(3, '2')
                      ->setParameter(4, '4')
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getSingleScalarResult();
    }
}