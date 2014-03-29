<?php
namespace PHPSC\Conference\Application\Action\Admin;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Application\Service\AuthenticationService;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\UI\Admin\Talks\Grid;
use PHPSC\Conference\UI\Main;

class Talks extends Controller
{
    /**
     * @Route("/")
     */
    public function showList()
    {
        $event = $this->getEventManagement()->findCurrentEvent();
        $talks = $this->getTalkManagement()->findByEvent($event);

        return new Main(
            new Grid(
                $event,
                $talks,
                $this->getAuthenticationService()->getLoggedUser()
            )
        );
    }

    /**
     * @return AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->get('authentication.service');
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
