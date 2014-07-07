<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\SponsorshipQuota;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

class SponsorRepository extends EntityRepository
{
    /**
     * @param Event $event
     * @param SponsorshipQuota $quota
     *
     * @return array
     */
    public function findByQuota(Event $event, SponsorshipQuota $quota)
    {
        $query = $this->createQueryBuilder('sponsor')
                      ->andwhere('sponsor.event = :event')
                      ->andwhere('sponsor.quota = :quota')
                      ->setParameter('event', $event)
                      ->setParameter('quota', $quota)
                      ->orderBy('sponsor.creationTime')
                      ->getQuery();

        return $query->getResult();
    }
}
