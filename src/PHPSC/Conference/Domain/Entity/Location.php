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
     * @Column(type="string", nullable=false)
     * @var string
     */
    private $name;

    /**
     * @Column(type="string", nullable=false)
     * @var string
     */
    private $description;

    /**
     * @Column(type="decimal")
     * @var float
     */
    private $longitude;

    /**
     * @Column(type="decimal")
     * @var float
     */
    private $latitude;

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
}