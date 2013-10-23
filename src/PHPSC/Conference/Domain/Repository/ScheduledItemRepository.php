<?php
namespace PHPSC\Conference\Domain\Repository;

use DateTime;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class ScheduledItemRepository extends EntityRepository
{
    /**
     * @param Event $event
     * @param DateTime $date
     * @return array
     */
    public function findByDate(Event $event, DateTime $date)
    {
        $query = $this->createQueryBuilder('item')
                      ->andWhere('item.event = ?1')
                      ->andWhere('item.startTime >= ?2')
                      ->andWhere('item.startTime <= ?3')
                      ->andWhere('item.active = TRUE')
                      ->setParameter(1, $event)
                      ->setParameter(2, $date->format('Y-m-d') . ' 00:00:00')
                      ->setParameter(3, $date->format('Y-m-d') . ' 23:59:59')
                      ->addOrderBy('item.startTime', 'ASC')
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getResult();
    }
}