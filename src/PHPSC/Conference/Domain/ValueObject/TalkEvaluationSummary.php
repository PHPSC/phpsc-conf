<?php
namespace PHPSC\Conference\Domain\ValueObject;

use Closure;
use PHPSC\Conference\Domain\Entity\Opinion;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\TalkEvaluation;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class TalkEvaluationSummary
{
    /**
     * @var Talk
     */
    protected $talk;

    /**
     * @var array<TalkEvaluation>
     */
    protected $evaluations;

    /**
     * @var array<Opinion>
     */
    protected $opinions;

    /**
     * @param Talk $talk
     * @param array<TalkEvaluation> $evaluations
     * @param array<Opinion> $evaluations
     */
    public function __construct(Talk $talk, array $evaluations, array $opinions)
    {
        $this->talk = $talk;
        $this->evaluations = $evaluations;
        $this->opinions = $opinions;
    }

    /**
     * @return int
     */
    public function getNumberOfLikes()
    {
        return count($this->getLikes());
    }

    /**
     * @return array<Opinion>
     */
    public function getLikes()
    {
        return array_values(
            array_filter(
                $this->opinions,
                function (Opinion $opinion) {
                    return $opinion->getLikes();
                }
            )
        );
    }

    /**
     * @return int
     */
    public function getNumberOfDislikes()
    {
        return count($this->getDislikes());
    }

    /**
     * @return array<Opinion>
     */
    public function getDislikes()
    {
        return array_values(
            array_filter(
                $this->opinions,
                function (Opinion $opinion) {
                    return !$opinion->getLikes();
                }
            )
        );
    }

    /**
     * @return number
     */
    public function getOriginalityEvaluation()
    {
        return $this->calculateAveragePoints(
            function (TalkEvaluation $evaluation) {
                return $evaluation->getOriginalityPoint();
            }
        );
    }

    /**
     * @return number
     */
    public function getRelevanceEvaluation()
    {
        return $this->calculateAveragePoints(
            function (TalkEvaluation $evaluation) {
                return $evaluation->getRelevancePoint();
            }
        );
    }

    /**
     * @return number
     */
    public function getTechnicalQualityEvaluation()
    {
        return $this->calculateAveragePoints(
            function (TalkEvaluation $evaluation) {
                return $evaluation->getTechnicalQualityPoint();
            }
        );
    }

    /**
     * @param Closure $pointsProvider
     * @return number
     */
    protected function calculateAveragePoints(Closure $pointsProvider)
    {
        $totalEvaluations = count($this->evaluations);
        $points = 0;

        if ($totalEvaluations == 0) {
            return $points;
        }

        foreach ($this->evaluations as $evaluation) {
            $points += $pointsProvider($evaluation);
        }

        return $points / $totalEvaluations;
    }

    /**
     * @return array<TalkEvaluation>
     */
    public function getNottedEvaluations()
    {
        return array_filter(
    	   $this->evaluations,
            function (TalkEvaluation $evaluation) {
                return $evaluation->hasNotes();
            }
        );
    }
}
