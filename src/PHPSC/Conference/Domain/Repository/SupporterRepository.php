<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Supporter;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

class SupporterRepository extends EntityRepository
{
    /**
     * @param Event $event
     * @return array
     */
    public function findByEvent(Event $event)
    {
        $query = $this->createQueryBuilder('supporter')
                      ->leftJoin('supporter.company', 'company')
                      ->andWhere('supporter.event = ?1')
                      ->setParameter(1, $event)
                      ->orderBy('company.name', 'ASC')
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getResult();
    }

    /**
     * @param int $id
     * @return Supporter
     */
    public function findOneById($id)
    {
        $query = $this->createQueryBuilder('supporter')
                      ->andWhere('supporter.id = ?1')
                      ->setParameter(1, $id)
                      ->setMaxResults(1)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }
}
