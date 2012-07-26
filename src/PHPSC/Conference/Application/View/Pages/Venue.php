<?php
namespace PHPSC\Conference\Application\View\Pages;

use PHPSC\Conference\Application\View\Main;

use \Lcobucci\DisplayObjects\Core\UIComponent;

class Venue extends UIComponent
{
    public function __construct()
    {
        Main::appendScript('http://maps.googleapis.com/maps/api/js?sensor=false');
        Main::appendScript($this->getBaseUrl() . '/js/gmaps.js');
        Main::appendScript($this->getBaseUrl() . '/js/venue.map.js');
    }
}