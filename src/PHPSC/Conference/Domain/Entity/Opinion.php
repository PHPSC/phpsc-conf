<?php
namespace PHPSC\Conference\Domain\Entity;

use PHPSC\Conference\Infra\Persistence\Entity;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\OpinionRepository")
 * @Table(
 *     "opinion",
 *     uniqueConstraints={@UniqueConstraint(name="opinion_index0",columns={"user_id", "talk_id"})},
 *     indexes={@Index(name="opinion_index1", columns={"likes"})}
 * )
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
     * @var User
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Talk", cascade={"all"})
     * @JoinColumn(name="talk_id", referencedColumnName="id", nullable=false)
     * @var Talk
     */
    private $talk;

    /**
     * @Column(type="boolean", options={"default" = 1}, nullable=false)
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
     * @param User $user
     * @param Talk $talk
     * @param boolean $likes
     * @return Opinion
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
