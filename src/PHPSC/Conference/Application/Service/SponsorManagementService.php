<?php
namespace PHPSC\Conference\Application\Service;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\Sponsor;
use PHPSC\Conference\Domain\Entity\SponsorshipQuota;
use PHPSC\Conference\Domain\Repository\SponsorRepository;

class SponsorManagementService
{
    /**
     * @var SponsorRepository
     */
    private $repository;

    /**
     * @param SponsorRepository $repository
     */
    public function __construct(SponsorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Event $event
     * @return array
     */
    public function findByQuota(Event $event, SponsorshipQuota $quota)
    {
        $sponsors = array();

        foreach ($this->repository->findByQuota($event, $quota) as $sponsor) {
            $sponsors[] = $this->toArray($sponsor);
        }

        return $sponsors;
    }

    /**
     * @param Supporter $sponsor
     * @return array
     */
    protected function toArray(Sponsor $sponsor)
    {
        return array(
            'id' => $sponsor->getId(),
            'quota' => array(
            	'id' => $sponsor->getQuota()->getId(),
                'title' => $sponsor->getQuota()->getTitle()
            ),
            'name' => $sponsor->getCompany()->getName(),
            'details' => $sponsor->getCompany()->getDetails(),
            'website' => $sponsor->getCompany()->getWebsite()
        );
    }
}
