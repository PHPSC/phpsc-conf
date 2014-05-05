<?php
namespace PHPSC\Conference\UI\Pages\Registration;

use DateTime;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\Domain\Service\RegistrationCostCalculator;

class Form extends Component
{
    /**
     * @var TalkManagementService
     */
    protected $talkService;

    /**
     * @var RegistrationCostCalculator
     */
    protected $costCalculator;

    /**
     * @param TalkManagementService $talkService
     * @param RegistrationCostCalculator $costCalculator
     */
    public function __construct(
        TalkManagementService $talkService,
        RegistrationCostCalculator $costCalculator
    ) {
        $this->talkService = $talkService;
        $this->costCalculator = $costCalculator;

        Main::appendScript($this->getUrl('js/vendor/jquery.price_format.2.0.min.js'));
        Main::appendScript($this->getUrl('js/attendee.create.js'));
    }

    /**
     * @return number
     */
    public function getCost($talksOnly)
    {
        return $this->costCalculator->getBaseCost(
            $this->event,
            $this->user,
            new DateTime(),
            $talksOnly
        );
    }

    /**
     * @return boolean
     */
    public function isSpeakerAndApprovalIntervalIsNotFinished()
    {
        //TODO this method is duplicate from another place
        return $this->talkService->userHasAnyTalk($this->user, $this->event)
               && new DateTime() <= $this->event->getTalkEvaluationEnd();
    }

    /**
     * @return StudentRules
     */
    public function getStudentRules()
    {
        return new StudentRules();
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
