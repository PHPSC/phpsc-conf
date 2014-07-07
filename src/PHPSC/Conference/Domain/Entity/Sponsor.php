<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\SponsorRepository")
 * @Table(
 *      "sponsor",
 *      uniqueConstraints={@UniqueConstraint(name="sponsor_index0",columns={"company_id", "event_id"})}
 * )
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Sponsor
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
     * @ManyToOne(targetEntity="Company", cascade={"all"})
     * @JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     *
     * @var Company
     */
    private $company;

    /**
     * @ManyToOne(targetEntity="Event")
     * @JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     *
     * @var Event
     */
    private $event;

    /**
     * @ManyToOne(targetEntity="SponsorshipQuota")
     * @JoinColumn(name="quota_id", referencedColumnName="id", nullable=false)
     *
     * @var SponsorshipQuota
     */
    private $quota;

    /**
     * @Column(type="datetime", name="creation_time")
     *
     * @var DateTime
     */
    private $creationTime;

    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     *
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     *
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     *
     * @return SponsorshipQuota
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     *
     * @param SponsorshipQuota $quota
     */
    public function setQuota(SponsorshipQuota $quota)
    {
        $this->quota = $quota;
    }

    /**
     *
     * @return DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     *
     * @param DateTime $creationTime
     */
    public function setCreationTime(DateTime $creationTime)
    {
        $this->creationTime = $creationTime;
    }
}
