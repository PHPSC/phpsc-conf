<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use InvalidArgumentException;
use PHPSC\Conference\Infra\Persistence\Entity;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\CompanyRepository")
 * @Table("company")
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Company implements Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", length=80, unique=true, name="social_id")
     * @var string
     */
    private $socialId;

    /**
     * @Column(type="string", length=80)
     * @var string
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="Logo", cascade={"all"})
     * @JoinColumn(name="logo_id", referencedColumnName="id", nullable=false)
     *
     * @var Logo
     */
    private $logo;

    /**
     * @Column(type="string", length=160, unique=true)
     *
     * @var string
     */
    private $email;

    /**
     * @Column(type="text", nullable=true)
     *
     * @var string
     */
    private $details;

    /**
     * @Column(type="string", length=30, nullable=true)
     * @var string
     */
    private $phone;

    /**
     * @Column(type="string", length=160)
     * @var string
     */
    private $website;

    /**
     * @Column(type="string", length=80, name="twitter_id", nullable=true)
     * @var string
     */
    private $twitterId;

    /**
     * @Column(type="string", nullable=true)
     * @var string
     */
    private $fanpage;

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
            throw new InvalidArgumentException(
                'O id deve ser maior que ZERO'
            );
        }

        $this->id = (int) $id;
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
     */
    public function setSocialId($socialId)
    {
        if (empty($socialId)) {
            throw new InvalidArgumentException('O CNPJ não pode ser vazio');
        }

        $this->socialId = (string) $socialId;
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
     * @return Logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param Logo $logo
     */
    public function setLogo(Logo $logo)
    {
        $this->logo = $logo;
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
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * @param string $twitterId
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;
    }

    /**
     * @return string
     */
    public function getFanpage()
    {
        return $this->fanpage;
    }

    /**
     * @param string $fanpage
     */
    public function setFanpage($fanpage)
    {
        $this->fanpage = $fanpage;
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
