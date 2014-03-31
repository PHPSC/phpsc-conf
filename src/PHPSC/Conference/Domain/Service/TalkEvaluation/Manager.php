<?php
namespace PHPSC\Conference\Domain\Service\TalkEvaluation;

use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Factory\TalkEvaluationFactory;
use PHPSC\Conference\Domain\Repository\TalkEvaluationRepository;
use PHPSC\Conference\Domain\Entity\TalkEvaluation;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Manager
{
    /**
     * @var TalkEvaluationRepository
     */
    protected $repository;

    /**
     * @var TalkEvaluationFactory
     */
    protected $factory;

    /**
     * @param TalkEvaluationRepository $repository
     * @param TalkEvaluationFactory $factory
     */
    public function __construct(
        TalkEvaluationRepository $repository,
        TalkEvaluationFactory $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

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
        $evaluation = $this->factory->create(
            $talk,
            $evaluator,
            $originalityPoint,
            $relevancePoint,
            $technicalQualityPoint,
            $notes
        );

        $this->repository->append($evaluation);

        return $evaluation;
    }

    /**
     * @param TalkEvaluation $evaluation
     * @param int $originalityPoint
     * @param int $relevancePoint
     * @param int $technicalQualityPoint
     * @param string $notes
     * @return TalkEvaluation
     */
    public function update(
        TalkEvaluation $evaluation,
        $originalityPoint,
        $relevancePoint,
        $technicalQualityPoint,
        $notes
    ) {
        $evaluation->setOriginalityPoint($originalityPoint);
        $evaluation->setRelevancePoint($relevancePoint);
        $evaluation->setTechnicalQualityPoint($technicalQualityPoint);
        $evaluation->setNotes($notes);

        $this->repository->update($evaluation);
    }
}
