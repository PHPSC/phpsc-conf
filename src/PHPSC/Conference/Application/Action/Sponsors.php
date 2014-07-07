<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Application\Service\SponsorManagementService;
use PHPSC\Conference\Domain\Repository\SponsorshipQuotaRepository;
use Lcobucci\ActionMapper2\Errors\BadRequestException;

class Sponsors extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function showSponsors()
    {
        $event = $this->getEventManager()->findCurrentEvent();
        $quota = $this->getQuotaRepository()->find($this->request->query->get('quota', 1));

        if ($quota === null) {
            throw new BadRequestException('Invalid quota');
        }

        $sponsors = $this->getSponsorService()->findByQuota($event, $quota);

        return json_encode($sponsors);
    }

    /**
     * @return SponsorManagementService
     */
    protected function getSponsorService()
    {
        return $this->get('sponsor.app.service');
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManager()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return SponsorshipQuotaRepository
     */
    protected function getQuotaRepository()
    {
        return $this->get('sponsorshipQuota.repository');
    }
}
