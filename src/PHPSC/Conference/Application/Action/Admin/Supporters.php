<?php
namespace PHPSC\Conference\Application\Action\Admin;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Domain\Service\SupporterManagementService;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Admin\Supporters\Grid;

class Supporters extends Controller
{
    /**
     * @Route("/")
     */
    public function showList()
    {
        $event = $this->getEventManagement()->findCurrentEvent();
        $supporters = $this->getSupporterManagement()->findByEvent($event);

        return new Main(new Grid($event, $supporters));
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return SupporterManagementService
     */
    protected function getSupporterManagement()
    {
        return $this->get('supporter.management.service');
    }
}
