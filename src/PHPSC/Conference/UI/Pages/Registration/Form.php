<?php
namespace PHPSC\Conference\UI\Pages\Registration;

use DateTime;
use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\UI\Main;

class Form extends UIComponent
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var TalkManagementService
     */
    protected $talkService;

    /**
     * @param User $user
     * @param Event $event
     * @param TalkManagementService $talkService
     */
    public function __construct(
        User $user,
        Event $event,
        TalkManagementService $talkService
    ) {
        $this->user = $user;
        $this->event = $event;
        $this->talkService = $talkService;

        Main::appendScript($this->getUrl('js/attendee.create.js'));
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
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
