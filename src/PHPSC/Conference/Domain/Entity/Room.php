<?php
namespace PHPSC\Conference\Domain\Entity;

use InvalidArgumentException;

/**
 * @Entity()
 * @Table("room")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Room
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", length=80)
     * @var string
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="Location")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     * @var Location
     */
    private $location;

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
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }
}
