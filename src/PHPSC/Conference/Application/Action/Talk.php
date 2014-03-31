<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\Domain\Service\OpinionManagementService;
use PHPSC\Conference\Domain\Service\TalkEvaluation\Locator;
use PHPSC\Conference\Domain\ValueObject\TalkEvaluationSummary;

class Talk extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function jsonTalk($id)
    {
        //TODO refactor this :D
        $talk = $this->getTalkManagement()->findById($id);

        $data = array(
            'id' => $talk->getId(),
            'speakers' => array(),
            'title' => $talk->getTitle(),
            'type' => $talk->getType()->getDescription(),
            'shortDescription' => $talk->getShortDescription(),
            'longDescription' => $talk->getLongDescription(),
            'tags' => $talk->getTags()
        );

        foreach ($talk->getSpeakers() as $speaker) {
            $data['speakers'][] = array(
                'id' => $speaker->getId(),
                'name' => $speaker->getName(),
                'avatar' => $speaker->getDefaultProfile()->getAvatar()
            );
        }

        $this->response->setContentType('application/json');

        return json_encode($data);
    }

    /**
     * @Route("/summary", methods={"GET"}, contentType={"application/json"})
     */
    public function jsonTalkSummary($id)
    {
        //TODO refactor this :D
        $talk = $this->getTalkManagement()->findById($id);

        $summary = new TalkEvaluationSummary(
            $talk,
            $this->getEvaluationLocator()->getByTalk($talk),
            $this->getOpinionManagement()->getByTalk($talk)
        );

        $data = array(
            'likes' => $summary->getNumberOfLikes(),
            'dislikes' => $summary->getNumberOfDislikes(),
            'originality' => $summary->getOriginalityEvaluation(),
            'relevance' => $summary->getRelevanceEvaluation(),
            'technicalQuality' => $summary->getTechnicalQualityEvaluation(),
            'notes' => array()
        );

        foreach ($summary->getNottedEvaluations() as $evaluation) {
            $data['notes'][] = array(
                'evaluator' => array(
                    'id' => $evaluation->getEvaluator()->getId(),
                    'name' => $evaluation->getEvaluator()->getName(),
                    'avatar' => $evaluation->getEvaluator()->getDefaultProfile()->getAvatar()
                ),
                'note' => $evaluation->getNotes()
            );
        }

        $this->response->setContentType('application/json');

        return json_encode($data);
    }

    /**
     * @return TalkManagementService
     */
    protected function getTalkManagement()
    {
        return $this->get('talk.management.service');
    }

    /**
     * @return OpinionManagementService
     */
    protected function getOpinionManagement()
    {
        return $this->get('opinion.management.service');
    }

    /**
     * @return Locator
     */
    protected function getEvaluationLocator()
    {
        return $this->get('talkEvaluation.locator');
    }
}
