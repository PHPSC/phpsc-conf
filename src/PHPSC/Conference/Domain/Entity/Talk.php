<?php
namespace PHPSC\Conference\Domain\Entity;

use \Doctrine\Common\Collections\ArrayCollection;
use \PHPSC\Conference\Infra\Persistence\Entity;
use \InvalidArgumentException;
use \DateTime;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\TalkRepository")
 * @Table("talk")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Talk implements Entity
{
    /**
     * @var string
     */
    const HIGH_COMPLEXITY = 'H';

    /**
     * @var string
     */
    const MEDIUM_COMPLEXITY = 'M';

    /**
     * @var string
     */
    const LOW_COMPLEXITY = 'L';

    /**
     * @Id
 	 * @Column(type="integer")
	 * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Event")
	 * @JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    private $event;

    /**
     * @ManyToMany(targetEntity="User")
     * @JoinTable(name="speaker",
     *      joinColumns={@JoinColumn(name="talk_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $speakers;

    /**
     * @Column(type="string", nullable=false)
     * @var string
     */
    private $title;

    /**
     * @ManyToOne(targetEntity="TalkType")
	 * @JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     * @var \PHPSC\Conference\Domain\Entity\TalkType
     */
    private $type;

    /**
     * @Column(type="string", nullable=false, name="short_description")
     * @var string
     */
    private $shortDescription;

    /**
     * @Column(type="string", nullable=false, name="long_description")
     * @var string
     */
    private $longDescription;

    /**
     * @Column(type="string", nullable=false)
     * @var string
     */
    private $complexity;

    /**
     * @Column(type="string")
     * @var string
     */
    private $tags;

    /**
     * @Column(type="datetime", name="start_time")
     * @var \DateTime
     */
    private $startTime;

    /**
     * @Column(type="boolean", nullable=false)
     * @var boolean
     */
    private $approved;

    /**
     * @Column(type="datetime", nullable=false, name="creation_time")
     * @var \DateTime
     */
    private $creationTime;

    /**
     * Inicializa o objeto
     */
    public function __construct()
    {
        $this->speakers = new ArrayCollection();
    }

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
                'O id deve ser maior ou igual à ZERO'
            );
        }

        $this->id = (int) $id;
    }

	/**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

	/**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

	/**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $speakers
     */
    public function setSpeakers(ArrayCollection $speakers)
    {
        $this->speakers = $speakers;
    }

	/**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

	/**
     * @param string $title
     */
    public function setTitle($title)
    {
        if (empty($title)) {
            throw new InvalidArgumentException('O título não pode ser vazio');
        }

        $this->title = (string) $title;
    }

	/**
     * @return \PHPSC\Conference\Domain\Entity\TalkType
     */
    public function getType()
    {
        return $this->type;
    }

	/**
     * @param \PHPSC\Conference\Domain\Entity\TalkType $type
     */
    public function setType(TalkType $type)
    {
        $this->type = $type;
    }

	/**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

	/**
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        if (empty($shortDescription)) {
            throw new InvalidArgumentException(
                'O resumo não pode ser vazio'
            );
        }

        $this->shortDescription = (string) $shortDescription;
    }

	/**
     * @return string$type
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

	/**
     * @param string $longDescription
     */
    public function setLongDescription($longDescription)
    {
        if (empty($longDescription)) {
            throw new InvalidArgumentException(
                'A descrição não pode ser vazia'
            );
        }

        $this->longDescription = (string) $longDescription;
    }

	/**
     * @return string
     */
    public function getComplexity()
    {
        return $this->complexity;
    }

	/**
     * @param string $complexity
     */
    public function setComplexity($complexity)
    {
        $possible = array(
            static::LOW_COMPLEXITY,
            static::MEDIUM_COMPLEXITY,
            static::HIGH_COMPLEXITY
        );

        if (!in_array($complexity, $possible)) {
            throw new InvalidArgumentException(
                'O valor inválido para nível'
            );
        }

        $this->complexity = $complexity;
    }

	/**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

	/**
     * @param string $tags
     */
    public function setTags($tags)
    {
        if ($tags !== null) {
            $tags = (string) $tags;

            if (empty($tags)) {
                throw new InvalidArgumentException(
                    'As tags não podem ser vazias'
                );
            }
        }

        $this->tags = $tags;
    }

	/**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

	/**
     * @param \DateTime $startTime
     */
    public function setStartTime(DateTime $startTime = null)
    {
        $this->startTime = $startTime;
    }

	/**
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

	/**
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        if (!is_bool($approved)) {
            throw new InvalidArgumentException(
                'Aprovado deve ser TRUE ou FALSE'
            );
        }

        $this->approved = $approved;
    }

	/**
     * @return \DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

	/**
     * @param \DateTime $creationTime
     */
    public function setCreationTime(DateTime $creationTime)
    {
        $this->creationTime = $creationTime;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\Event $event
     * @param \PHPSC\Conference\Domain\Entity\User $speaker
     * @param \PHPSC\Conference\Domain\Entity\TalkType $type
     * @param string $title
     * @param string $shortDescription
     * @param string $longDescription
     * @param string $complexity
     * @param string $tags
     * @return \PHPSC\Conference\Domain\Entity\Talk
     */
    public static function create(
        Event $event,
        User $speaker,
        TalkType $type,
        $title,
        $shortDescription,
        $longDescription,
        $complexity,
        $tags = null
    ) {
        $talk = new static();

        $talk->setEvent($event);
        $talk->setType($type);
        $talk->setTitle($title);
        $talk->setComplexity($complexity);
        $talk->setTags($tags);
        $talk->setShortDescription($shortDescription);
        $talk->setLongDescription($longDescription);
        $talk->setCreationTime(new DateTime());
        $talk->setApproved(false);
        $talk->getSpeakers()->add($speaker);

        return $talk;
    }
}