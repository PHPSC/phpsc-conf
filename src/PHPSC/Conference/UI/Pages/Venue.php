<?php
namespace PHPSC\Conference\UI\Pages;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\Infra\UI\Component;

class Venue extends Component
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
        Main::appendScript($this->getUrl('js/gmaps.js'));
        Main::appendScript($this->getUrl('js/venue.map.js'));

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
