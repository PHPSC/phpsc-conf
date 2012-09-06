<?php
namespace PHPSC\Conference\Domain\Entity;

use \PHPSC\Conference\Infra\Persistence\Entity;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\OpinionRepository")
 * @Table("opinion")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Opinion implements Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"})
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var \PHPSC\Conference\Domain\Entity\User
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Talk", cascade={"all"}, inversedBy="opinions")
     * @JoinColumn(name="talk_id", referencedColumnName="id", nullable=false)
     * @var \PHPSC\Conference\Domain\Entity\Talk
     */
    private $talk;

    /**
     * @Column(type="boolean", nullable=false)
     * @var boolean
     */
    private $likes;

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
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

	/**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

	/**
     * @return \PHPSC\Conference\Domain\Entity\Talk
     */
    public function getTalk()
    {
        return $this->talk;
    }

	/**
     * @param \PHPSC\Conference\Domain\Entity\Talk $talk
     */
    public function setTalk(Talk $talk)
    {
        $this->talk = $talk;
    }
	/**
     * @return boolean
     */
    public function getLikes()
    {
        return $this->likes;
    }

	/**
     * @param boolean $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @param \PHPSC\Conference\Domain\Entity\User $user
     * @param \PHPSC\Conference\Domain\Entity\Talk $talk
     * @param boolean $likes
     * @return \PHPSC\Conference\Domain\Entity\Opinion
     */
    public static function create(User $user, Talk $talk, $likes)
    {
        $opinion = new static();

        $opinion->setUser($user);
        $opinion->setTalk($talk);
        $opinion->setLikes($likes);

        return $opinion;
    }
}