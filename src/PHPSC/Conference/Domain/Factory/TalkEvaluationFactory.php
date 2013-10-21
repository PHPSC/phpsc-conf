<?php
namespace PHPSC\Conference\Domain\Factory;

use DateTime;
use PHPSC\Conference\Domain\Entity\TalkEvaluation;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\User;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class TalkEvaluationFactory
{
    /**
     * @param Talk $talk
     * @param User $evaluator
     * @param int $originalityPoint
     * @param int $relevancePoint
     * @param int $technicalQualityPoint
     * @param string $notes
     * @return TalkEvaluation
     */
    public function create(
        Talk $talk,
        User $evaluator,
        $originalityPoint,
        $relevancePoint,
        $technicalQualityPoint,
        $notes
    ) {
        $evaluation = new TalkEvaluation();
        $evaluation->setTalk($talk);
        $evaluation->setEvaluator($evaluator);
        $evaluation->setOriginalityPoint($originalityPoint);
        $evaluation->setRelevancePoint($relevancePoint);
        $evaluation->setTechnicalQualityPoint($technicalQualityPoint);
        $evaluation->setNotes($notes);
        $evaluation->setCreationTime(new DateTime());

        return $evaluation;
    }
}
