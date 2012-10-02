<?php
namespace PHPSC\Conference\Application\Action;

use \PHPSC\Conference\Application\View\Pages\Talks\Index;
use \Lcobucci\ActionMapper2\Routing\Annotation\Route;
use \Lcobucci\ActionMapper2\Routing\Controller;
use \PHPSC\Conference\Application\View\Main;

class Talks extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function renderIndex()
    {
        $event = $this->getEventManagement()->findCurrentEvent();

        return Main::create(
            new Index(
                $event,
                $this->getTalkManagement()->eventHasAnyApprovedTalk($event)
            ),
            $this->application,
            'Confira aqui a grade de palestras aprovadas para o evento!'
        );
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\TalkManagementService
     */
    protected function getTalkManagement()
    {
    	return $this->get('talk.management.service');
    }
}