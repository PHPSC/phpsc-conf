<?php
namespace PHPSC\Conference\Application\Action\Admin;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Admin\Credentialing\Grid;

class Credentialing extends Controller
{
    /**
     * @Route("/")
     */
    public function showList()
    {
        return new Main(
            new Grid(
                $this->get('attendee.management.service')
                     ->findByEvent(Component::get('event'))
            )
        );
    }
}
