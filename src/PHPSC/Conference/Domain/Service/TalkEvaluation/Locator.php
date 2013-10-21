<?php
namespace PHPSC\Conference\Domain\Service\TalkEvaluation;

use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Repository\TalkEvaluationRepository;
use PHPSC\Conference\Domain\Entity\User;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Locator
{
    /**
     * @var TalkEvaluationRepository
     */
    protected $repository;

    /**
     * @param TalkEvaluationRepository $repository
     */
    public function __construct(TalkEvaluationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Talk $talk
     * @return array<\PHPSC\Conference\Domain\Entity\TalkEvaluation>
     */
    public function getByTalk(Talk $talk)
    {
        return $this->repository->findByTalk($talk);
    }

    /**
     * @param Talk $talk
     * @param User $evaluator
     * @return array<\PHPSC\Conference\Domain\Entity\TalkEvaluation>
     */
    public function search(Talk $talk = null, User $evaluator = null)
    {
        return $this->repository->search($talk, $evaluator);
    }
}
