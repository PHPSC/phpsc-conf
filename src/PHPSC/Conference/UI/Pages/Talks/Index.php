<?php
namespace PHPSC\Conference\UI\Pages\Talks;

use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;

class Index extends Component
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var boolean
     */
    protected $hasApprovedTalks;

    /**
     * @param Event $event
     * @param boolean $hasApprovedTalks
     */
    public function __construct(Event $event, $hasApprovedTalks = false)
    {
        $this->event = $event;
        $this->hasApprovedTalks = $hasApprovedTalks;

        Main::appendScript($this->getUrl('js/talks/schedule.js'));
    }

    /**
     * @return Event
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

    /**
     * @return TalkWindow
     */
    public function getWindow()
    {
        return new TalkWindow();
    }
}
