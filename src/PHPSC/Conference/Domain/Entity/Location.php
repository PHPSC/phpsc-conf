<?php
namespace PHPSC\Conference\Domain\Entity;

use \PHPSC\Conference\Infra\Persistence\Entity;
use \InvalidArgumentException;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\LocationRepository")
 * @Table("location")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Location implements Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", length=60, nullable=false)
     * @var string
     */
    private $name;

    /**
     * @Column(type="string", length=60, nullable=true)
     * @var string
     */
    private $website;

    /**
     * @Column(type="text")
     * @var string
     */
    private $description;

    /**
     * @Column(type="decimal", precision=13, scale=7, nullable=true)
     * @var float
     */
    private $longitude;

    /**
     * @Column(type="decimal", precision=13, scale=7, nullable=true)
     * @var float
     */
    private $latitude;

    /**
     * @ManyToOne(targetEntity="Logo", cascade={"all"})
     * @JoinColumn(name="logo_id", referencedColumnName="id", nullable=true)
     *
     * @var Logo
     */
    private $logo;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        if (empty($description)) {
            throw new InvalidArgumentException(
                'A descrição não pode ser vazia'
            );
        }

        $this->description = (string) $description;
    }

    /**
     * @return number
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param number $longitude
     */
    public function setLongitude($longitude)
    {
        if ($longitude !== null) {
            $longitude = (float) $longitude;
        }

        $this->longitude = $longitude;
    }

    /**
     * @return number
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param number $latitude
     */
    public function setLatitude($latitude)
    {
        if ($latitude !== null) {
            $latitude = (float) $latitude;
        }

        $this->latitude = $latitude;
    }

    /**
     * @return boolean
     */
    public function hasGeoPoint()
    {
        return $this->getLatitude() !== null && $this->getLongitude() !== null;
    }

    /**
     * @return Logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param Logo $website
     */
    public function setLogo(Logo $logo = null)
    {
        $this->logo = $logo;
    }

    /**
     * @return boolean
     */
    public function hasLogo()
    {
        return $this->getLogo() !== null;
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
}
