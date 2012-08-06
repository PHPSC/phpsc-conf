<?php
namespace PHPSC\Conference\Application\View\Pages\Registration;


use \PHPSC\Conference\Domain\Service\TalkManagementService;
use \Lcobucci\DisplayObjects\Core\UIComponent;
use \PHPSC\Conference\Application\View\Main;
use \PHPSC\Conference\Domain\Entity\Event;
use \PHPSC\Conference\Domain\Entity\User;

class Form extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\User
     */
    protected $user;

    /**
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    protected $event;

    /**
     * @var \PHPSC\Conference\Domain\Service\TalkManagementService
     */
    protected $talkService;

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Service\TalkManagementService $talkService
     */
    public function __construct(
        User $user,
        Event $event,
        TalkManagementService $talkService
    ) {
        $this->user = $user;
        $this->event = $event;
        $this->talkService = $talkService;

        Main::appendScript($this->getBaseUrl() . '/js/attendee.create.js');
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
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
        return $this->event->getRegistrationCost(
            $this->user,
            $this->talkService
        );
    }

    /**
     * @return boolean
     */
    public function isSpeakerAndApprovalIntervalIsNotFinished()
    {
        return $this->talkService->userHasAnyTalk($this->user, $this->event)
               && new \DateTime() <= $this->event->getTalkApprovalEnd();
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