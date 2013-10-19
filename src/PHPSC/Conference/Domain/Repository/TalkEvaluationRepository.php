<?php
namespace PHPSC\Conference\Domain\Repository;

use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Infra\Persistence\EntityRepository;

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
}
