<?php
namespace PHPSC\Conference\Domain\Repository;

use \PHPSC\Conference\Infra\Persistence\EntityRepository;

class EventRepository extends EntityRepository
{
    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function findCurrentEvent()
    {
        $query = $this->createQueryBuilder('event')
                      ->orderBy('event.id', 'DESC')
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getSingleResult();
    }
}