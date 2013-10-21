<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Infra\Persistence\EntityRepository;
use PHPSC\Conference\Domain\Entity\User;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class TalkEvaluationRepository extends EntityRepository
{
    /**
     * @param Talk $talk
     * @return array<\PHPSC\Conference\Domain\Entity\TalkEvaluation>
     */
    public function findByTalk(Talk $talk)
    {
        $query = $this->createQueryBuilder('evaluation')
                      ->andWhere('evaluation.talk = ?1')
                      ->setParameter(1, $talk)
                      ->getQuery();

        $query->useQueryCache(true);

        return $query->getResult();
    }

    /**
     * @param Talk $talk
     * @param User $evaluator
     * @return array<\PHPSC\Conference\Domain\Entity\TalkEvaluation>
     */
    public function search(Talk $talk = null, User $evaluator = null)
    {
        $builder = $this->createQueryBuilder('evaluation');

        if ($talk) {
            $builder->andWhere('evaluation.talk = ?1')
                    ->setParameter(1, $talk);
        }

        if ($evaluator) {
            $builder->andWhere('evaluation.evaluator = ?2')
                    ->setParameter(2, $evaluator);
        }

        $query = $builder->getQuery();
        $query->useQueryCache(true);

        return $query->getResult();
    }
}
