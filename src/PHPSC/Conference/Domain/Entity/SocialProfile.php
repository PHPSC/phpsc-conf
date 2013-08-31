<?php
namespace PHPSC\Conference\Domain\Entity;

use InvalidArgumentException;

/**
 * @Entity
 * @Table(
 *     "social_profile",
 *     uniqueConstraints={
 *         @UniqueConstraint(name="social_profile_index0",columns={"provider", "user_id"}),
 *         @UniqueConstraint(name="social_profile_index1",columns={"provider", "social_id"}),
 *     },
 *     indexes={
 *         @Index(name="social_profile_index2", columns={"default"})
 *     }
 * )
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class SocialProfile
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="profiles")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @Column(type="string", length=20, nullable=false)
     * @var string
     */
    private $provider;

    /**
     * @Column(type="string", name="social_id", length=80, nullable=false)
     * @var string
     */
    private $socialId;

    /**
     * @Column(type="string", length=80, nullable=false)
     * @var string
     */
    private $username;

    /**
     * @Column(type="string", length=80, nullable=false)
     * @var string
     */
    private $name;

    /**
     * @Column(type="string", length=160, nullable=true)
     * @var string
     */
    private $email;

    /**
     * @Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $avatar;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    private $default;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @throws InvalidArgumentException
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
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * @throws InvalidArgumentException
     */
    public function setProvider($provider)
    {
        if (empty($provider)) {
            throw new InvalidArgumentException('O nome da rede social não pode ser vazio');
        }

        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getSocialId()
    {
        return $this->socialId;
    }

    /**
     * @param string $socialId
     * @throws InvalidArgumentException
     */
    public function setSocialId($socialId)
    {
        if (empty($socialId)) {
            throw new InvalidArgumentException('O id na rede social não pode ser vazio');
        }

        $this->socialId = (string) $socialId;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @throws InvalidArgumentException
     */
    public function setUsername($username)
    {
        if (empty($username)) {
            throw new InvalidArgumentException('O login na rede social não pode ser vazio');
        }

        $this->username = (string) $username;
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
     * @throws InvalidArgumentException
     */
    public function setName($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('O nome na rede social não pode ser vazio');
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
     * @throws InvalidArgumentException
     */
    public function setEmail($email)
    {
        if ($email === null) {
            return ;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('O email na rede social deve ser válido');
        }

        $this->email = (string) $email;
    }

    /**
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @throws InvalidArgumentException
     */
    public function setAvatar($avatar)
    {
        if ($avatar === null) {
            return ;
        }

        if (!filter_var($avatar, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('A URL do avatar na rede social deve ser válida');
        }

        $this->avatar = $avatar;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param boolean $default
     */
    public function setDefault($default)
    {
        $this->default = (bool) $default;
    }
}
