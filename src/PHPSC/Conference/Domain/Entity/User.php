<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use PHPSC\Conference\Infra\Persistence\Entity;

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
     * @Column(type="string", length=80, nullable=false)
     * @var string
     */
    private $name;

    /**
     * @Column(type="string", length=160, nullable=false, unique=true)
     * @var string
     */
    private $email;

    /**
     * @OneToMany(targetEntity="SocialProfile", mappedBy="user", cascade={"all"})
     * @var ArrayCollection
     */
    private $profiles;

    /**
     * @Column(type="text", nullable=true)
     * @var string
     */
    private $bio;

    /**
     * @Column(type="datetime", nullable=false, name="creation_time")
     * @var \DateTime
     */
    private $creationTime;

    /**
     * @Column(type="boolean", options={"default" = 0}, nullable=false)
     * @var boolean
     */
    private $admin;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->profiles = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * @param ArrayCollection $profiles
     */
    public function setProfiles(ArrayCollection $profiles)
    {
        $this->profiles = $profiles;
    }

    /**
     * @param SocialProfile $profile
     */
    public function addProfile(SocialProfile $profile)
    {
        $this->profiles->add($profile);
        $profile->setUser($this);
    }

    /**
     * @return boolean
     */
    public function hasAnyProfile()
    {
        return $this->getProfiles()->count() > 0;
    }

    /**
     * @return SocialProfile
     */
    public function getDefaultProfile()
    {
        foreach ($this->getProfiles() as $profile) {
            if ($profile->isDefault()) {
                return $profile;
            }
        }
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
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @param boolean $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = (boolean) $admin;
    }

    /**
     * @param Event $event
     * @return boolean
     */
    public function hasManagementPrivilegesOn(Event $event)
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $event->isEvaluator($this);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $bio
     * @return User
     */
    public static function create($name, $email, $bio = null)
    {
        $user = new static();
        $user->setName($name);
        $user->setEmail($email);
        $user->setBio($bio);
        $user->setCreationTime(new DateTime());
        $user->setAdmin(false);

        return $user;
    }
}
