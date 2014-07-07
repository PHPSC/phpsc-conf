<?php
namespace PHPSC\Conference\Domain\Entity;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\SponsorshipQuotaRepository")
 * @Table("sponsorship_quota")
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class SponsorshipQuota
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", length=45)
     *
     * @var string
     */
    private $title;

    /**
     * @Column(type="json_array")
     *
     * @var array
     */
    private $benefits;

    /**
     * @Column(type="decimal", precision=8, scale=2)
     *
     * @var float
     */
    private $cost;

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     *
     * @return array
     */
    public function getBenefits()
    {
        return $this->benefits;
    }

    /**
     *
     * @param array $benefits
     */
    public function setBenefits(array $benefits)
    {
        $this->benefits = $benefits;
    }

    /**
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     *
     * @param number $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }
}
