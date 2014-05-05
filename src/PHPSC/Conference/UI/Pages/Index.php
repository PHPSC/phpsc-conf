<?php
namespace PHPSC\Conference\UI\Pages;

use DateTime;
use PHPSC\Conference\Infra\UI\Component;

class Index extends Component
{
    /**
     * @var boolean
     */
    protected $hasApprovedTalks;

    /**
     * @param boolean $hasApprovedTalks
     */
    public function __construct($hasApprovedTalks)
    {
        $this->hasApprovedTalks = $hasApprovedTalks;
    }

    /**
     * @return boolean
     */
    protected function isSubmissionInterval()
    {
        return $this->event->isSubmissionsPeriod(new DateTime());
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
