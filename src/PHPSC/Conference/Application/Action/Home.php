<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Infra\UI\Component;
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
        return new Main(new Venue());
    }
}
