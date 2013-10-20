<?php
namespace PHPSC\Conference\Domain\Service\TalkEvaluation;

use PHPSC\Conference\Domain\Factory\TalkEvaluationFactory;
use PHPSC\Conference\Domain\Repository\TalkEvaluationRepository;

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
}
