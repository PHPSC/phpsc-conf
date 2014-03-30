<?php
namespace PHPSC\Conference\Application\Action\Admin;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\UI\Admin\Talks\Grid;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\Infra\UI\Component;

class Talks extends Controller
{
    /**
     * @Route("/")
     */
    public function showList()
    {
        return new Main(
            new Grid(
                $this->getTalkManagement()
                     ->findByEvent(Component::get('event'))
            )
        );
    }

    /**
     * @return TalkManagementService
     */
    protected function getTalkManagement()
    {
        return $this->get('talk.management.service');
    }
}
