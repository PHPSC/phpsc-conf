<?php
namespace PHPSC\Conference\Application\View\Pages\Registration;

use PHPSC\Conference\Application\View\Main;

use \PHPSC\Conference\Domain\Entity\Event;
use \Lcobucci\DisplayObjects\Core\UIComponent;

class Confirmation extends UIComponent
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
        $this->event = $event;

        Main::appendScript($this->getBaseUrl() . '/js/registration.share.js');
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}