<?php
namespace PHPSC\Conference\UI\Pages\Talks;

use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;

class Index extends Component
{
    /**
     * @var boolean
     */
    protected $hasApprovedTalks;

    /**
     * @param boolean $hasApprovedTalks
     */
    public function __construct($hasApprovedTalks = false)
    {
        $this->hasApprovedTalks = $hasApprovedTalks;

        Main::appendScript($this->getUrl('js/talks/schedule.js'));
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
