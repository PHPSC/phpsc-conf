<?php
namespace PHPSC\Conference\Application\View\Pages;

use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Application\View\Main;
use \Lcobucci\DisplayObjects\Core\UIComponent;

class Venue extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    protected $event;

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     */
    public function __construct(Event $event)
    {
        Main::appendScript('http://maps.googleapis.com/maps/api/js?sensor=false');
        Main::appendScript($this->getBaseUrl() . '/js/gmaps.js');
        Main::appendScript($this->getBaseUrl() . '/js/venue.map.js');

        $this->event = $event;
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return boolean
     */
    public function displayMap()
    {
        return $this->getEvent()->getLocation()->hasGeoPoint();
    }
}
