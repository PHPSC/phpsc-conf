<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use InvalidArgumentException;

/**
 * @Entity
 * @Table("supporter")
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Supporter
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Company")
     * @JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     * @var Company
     */
    private $company;

    /**
     * @ManyToOne(targetEntity="Event")
     * @JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     * @var Event
     */
    private $event;

    /**
     * @Column(type="text")
     * @var string
     */
    private $details;

    /**
     * @Column(type="datetime", name="creation_time")
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
        if ($id <= 0) {
            throw new InvalidArgumentException(
                'O id deve ser maior que ZERO'
            );
        }

        $this->id = (int) $id;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails($details)
    {
        if (empty($details)) {
            throw new InvalidArgumentException(
                'Os detalhes de apoio não podem ser vazios'
            );
        }

        $this->details = (string) $details;
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