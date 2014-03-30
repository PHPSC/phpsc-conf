<?php
namespace PHPSC\Conference\UI\Pages;

use PHPSC\Conference\UI\Main;
use PHPSC\Conference\Infra\UI\Component;

class Venue extends Component
{
    public function __construct()
    {
        Main::appendScript('http://maps.googleapis.com/maps/api/js?sensor=false');
        Main::appendScript($this->getUrl('js/gmaps.js'));
        Main::appendScript($this->getUrl('js/venue.map.js'));
    }

    /**
     * @return boolean
     */
    public function displayMap()
    {
        return $this->event->getLocation()->hasGeoPoint();
    }
}
