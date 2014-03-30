<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Pages\Talks\Index;

class Talks extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function renderIndex()
    {
        return new Main(new Index($this->hasApprovedTalks()));
    }

    /**
     * @return boolean
     */
    protected function hasApprovedTalks()
    {
        return $this->get('talk.management.service')
                    ->eventHasAnyApprovedTalk(Component::get('event'));
    }
}
