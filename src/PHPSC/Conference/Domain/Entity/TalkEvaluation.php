<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use InvalidArgumentException;
use PHPSC\Conference\Infra\Persistence\Entity;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\TalkEvaluationRepository")
 * @Table("talk_evaluation")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class TalkEvaluation implements Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Talk")
     * @JoinColumn(name="talk_id", referencedColumnName="id")
     * @var Talk
     */
    private $talk;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="evaluator_id", referencedColumnName="id")
     * @var User
     */
    private $evaluator;

    /**
     * @Column(type="integer", name="originality_point")
     * @var int
     */
    private $originalityPoint;

    /**
     * @Column(type="integer", name="relevance_point")
     * @var int
     */
    private $relevancePoint;

    /**
     * @Column(type="integer", name="technical_quality_point")
     * @var int
     */
    private $technicalQualityPoint;

    /**
     * @Column(type="text", nullable=true)
     * @var string
     */
    private $notes;

    /**
     * @Column(type="datetime", name="creation_time")
     * @var DateTime
     */
    private $creationTime;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('O id deve ser maior que ZERO');
        }

        $this->id = (int) $id;
    }

    /**
     * @return Talk
     */
    public function getTalk()
    {
        return $this->talk;
    }

    /**
     * @param Talk $talk
     */
    public function setTalk(Talk $talk)
    {
        $this->talk = $talk;
    }

    /**
     * @return User
     */
    public function getEvaluator()
    {
        return $this->evaluator;
    }

    /**
     * @param User $evaluator
     */
    public function setEvaluator(User $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    /**
     * @return int
     */
    public function getOriginalityPoint()
    {
        return $this->originalityPoint;
    }

    /**
     * @param int $originalityPoint
     */
    public function setOriginalityPoint($originalityPoint)
    {
        if ($originalityPoint < 0 || $originalityPoint > 5) {
            throw new InvalidArgumentException(
                'A pontuação de originalidade deve ser entre 0 e 5'
            );
        }

        $this->originalityPoint = $originalityPoint;
    }

    /**
     * @return int
     */
    public function getRelevancePoint()
    {
        return $this->relevancePoint;
    }

    /**
     * @param int $relevancePoint
     */
    public function setRelevancePoint($relevancePoint)
    {
        if ($relevancePoint < 0 || $relevancePoint > 5) {
            throw new InvalidArgumentException(
                'A pontuação de relevância deve ser entre 0 e 5'
            );
        }

        $this->relevancePoint = $relevancePoint;
    }

    /**
     * @return int
     */
    public function getTechnicalQualityPoint()
    {
        return $this->technicalQualityPoint;
    }

    /**
     * @param int $technicalQualityPoint
     */
    public function setTechnicalQualityPoint($technicalQualityPoint)
    {
        if ($technicalQualityPoint < 0 || $technicalQualityPoint > 5) {
            throw new InvalidArgumentException(
                'A pontuação de qualidade técnica deve ser entre 0 e 5'
            );
        }

        $this->technicalQualityPoint = $technicalQualityPoint;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return boolean
     */
    public function hasNotes()
    {
        return $this->getNotes() !== null;
    }

    /**
     * @return DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param DateTime $creationTime
     */
    public function setCreationTime(DateTime $creationTime)
    {
        $this->creationTime = $creationTime;
    }
}
