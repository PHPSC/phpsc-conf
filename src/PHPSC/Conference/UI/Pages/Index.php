<?php
namespace PHPSC\Conference\UI\Pages;

use DateTime;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Infra\UI\Component;

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
    protected function isRegularRegistrationPeriod()
    {
        return $this->event->isRegularRegistrationPeriod(new DateTime());
    }

    /**
     * @return boolean
     */
    protected function isLateRegistrationPeriod()
    {
        return $this->event->isLateRegistrationPeriod(new DateTime());
    }

    /**
     * @return boolean
     */
    protected function isApprovalPeriod()
    {
        return !$this->hasApprovedTalks;
    }
}
