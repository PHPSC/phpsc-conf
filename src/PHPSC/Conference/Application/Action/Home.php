<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Pages\About;
use PHPSC\Conference\UI\Pages\Index;
use PHPSC\Conference\UI\Pages\Venue;

class Home extends Controller
{
    /**
     * @Route
     */
    public function displayHome()
    {
        $event = $this->getEventManagement()->findCurrentEvent();

        return new Main(
            new Index(
                $event,
                $this->getTalkManagement()->eventHasAnyApprovedTalk($event)
            )
        );
    }

    /**
     * @Route("/about")
     */
    public function displayAbout()
    {
        return new Main(new About());
    }

    /**
     * @Route("/venue")
     */
    public function displayVenue()
    {
        return new Main(new Venue($this->getEventManagement()->findCurrentEvent()));
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return TalkManagementService
     */
    protected function getTalkManagement()
    {
        return $this->get('talk.management.service');
    }
}
