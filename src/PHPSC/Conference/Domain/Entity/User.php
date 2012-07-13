<?php
namespace PHPSC\Conference\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use \PHPSC\Conference\Infra\Persistence\Entity;
use \InvalidArgumentException;
use \DateTime;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\UserRepository")
 * @Table("user")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class User implements Entity
{
    /**
     * @Id
 	 * @Column(type="integer")
	 * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", nullable=false)
     * @var string
     */
    private $name;

    /**
     * @Column(type="string", nullable=false)
     * @var string
     */
    private $email;

    /**
     * @Column(type="string", nullable=false, name="twitter_user")
     * @var string
     */
    private $twitterUser;

    /**
     * @Column(type="string", name="github_user")
     * @var string
     */
    private $githubUser;

    /**
     * @Column(type="string")
     * @var string
     */
    private $bio;

    /**
     * @Column(type="datetime", nullable=false, name="creation_time")
     * @var \DateTime
     */
    private $creationTime;

    /**
     * @ManyToMany(targetEntity="Talk")
     * @JoinTable(name="interest",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="talk_id", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $interests;

    /**
     * Inicializador do objeto
     */
    public function __construct()
    {
        $this->interests = new ArrayCollection();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

	/**
     * @param string $name
     */
    public function setName($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('O nome não pode ser vazio');
        }

        $this->name = (string) $name;
    }

	/**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

	/**
     * @param string $email
     */
    public function setEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('O email deve ser válido');
        }

        $this->email = (string) $email;
    }

	/**
     * @return string
     */
    public function getTwitterUser()
    {
        return $this->twitterUser;
    }

	/**
     * @param string $twitterUser
     */
    public function setTwitterUser($twitterUser)
    {
        if (empty($twitterUser)) {
            throw new InvalidArgumentException(
                'O nome do usuário no twitter não pode ser vazio'
            );
        }

        $this->twitterUser = (string) $twitterUser;
    }

	/**
     * @return string
     */
    public function getGithubUser()
    {
        return $this->githubUser;
    }

	/**
     * @param string $githubUser
     */
    public function setGithubUser($githubUser)
    {
        if ($githubUser !== null) {
            $githubUser = (string) $githubUser;

            if (empty($githubUser)) {
                throw new InvalidArgumentException(
                    'O nome do usuário do github não pode ser vazio'
                );
            }
        }

        $this->githubUser = $githubUser;
    }

	/**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

	/**
     * @param string $bio
     */
    public function setBio($bio)
    {
        if ($bio !== null) {
            $bio = (string) $bio;

            if (empty($bio)) {
                throw new InvalidArgumentException(
                    'A biografia não pode ser vazia'
                );
            }
        }

        $this->bio = $bio;
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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInterests()
    {
        return $this->interests;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $interests
     */
    public function setInterests(ArrayCollection $interests)
    {
        $this->interests = $interests;
    }

    /**
     * @param string $name
     * @param string $twitterUser
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public static function create(
        $name,
        $twitterUser,
        $email = null,
        $githubUser = null,
        $bio = null
    ) {
        $user = new static();
        $user->setName($name);
        $user->setTwitterUser($twitterUser);
        $user->setGithubUser($githubUser);
        $user->setBio($bio);
        $user->setCreationTime(new DateTime());

        if ($email) {
            $user->setEmail($email);
        }

        return $user;
    }

    /**
     * @param \stdClass $twitterData
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public static function createFromTwitterData(\stdClass $twitterData)
    {
        return static::create(
            $twitterData->name,
            $twitterData->screen_name,
            null,
            null,
            !empty($twitterData->description) ? $twitterData->description : null
        );
    }
}