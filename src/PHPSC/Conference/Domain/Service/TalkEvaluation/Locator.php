<?php
namespace PHPSC\Conference\Domain\Service\TalkEvaluation;

use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Repository\TalkEvaluationRepository;

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
     * @return array
     */
    public function getByTalk(Talk $talk)
    {
        return $this->repository->findByTalk($talk);
    }
}
