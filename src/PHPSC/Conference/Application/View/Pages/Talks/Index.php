<?php
namespace PHPSC\Conference\Application\View\Pages\Talks;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \PHPSC\Conference\Domain\Entity\Event;

class Index extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    protected $event;

    /**
     * @var boolean
     */
    protected $hasApprovedTalks;

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param boolean $hasApprovedTalks
     */
    public function __construct(Event $event, $hasApprovedTalks = false)
    {
        $this->event = $event;
        $this->hasApprovedTalks = $hasApprovedTalks;
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
    public function hasApprovedTalks()
    {
        return $this->hasApprovedTalks;
    }
}