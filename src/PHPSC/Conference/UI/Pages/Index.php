<?php
namespace PHPSC\Conference\UI\Pages;

use DateTime;
use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\Domain\Entity\Event;

class Index extends UIComponent
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
    public function __construct(Event $event, $hasApprovedTalks)
    {
        $this->event = $event;
        $this->hasApprovedTalks = $hasApprovedTalks;
    }

    /**
     * @return boolean
     */
    protected function isSubmissionInterval()
    {
        return $this->event->isSubmissionsInterval(new DateTime());
    }

    /**
     * @return boolean
     */
    protected function isApprovalPeriod()
    {
        return !$this->hasApprovedTalks;
    }
}
