<?php
namespace PHPSC\Conference\UI\Pages\Registration;

use DateTime;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;

class Form extends Component
{
    /**
     * @var TalkManagementService
     */
    protected $talkService;

    /**
     * @param TalkManagementService $talkService
     */
    public function __construct(TalkManagementService $talkService)
    {
        $this->talkService = $talkService;

        Main::appendScript($this->getUrl('js/attendee.create.js'));
    }

    /**
     * @return number
     */
    public function getCost()
    {
        return $this->event->getRegistrationBaseCost(
            $this->user,
            $this->talkService
        );
    }

    /**
     * @return boolean
     */
    public function isSpeakerAndApprovalIntervalIsNotFinished()
    {
        //TODO this method is duplicate from another place
        return $this->talkService->userHasAnyTalk($this->user, $this->event)
               && new DateTime() <= $this->event->getTalkApprovalEnd();
    }

    /**
     * @return boolean
     */
    public function isSpeakerWithApprovedTalks()
    {
        return $this->talkService->userHasAnyApprovedTalk(
            $this->user,
            $this->event
        );
    }
}
