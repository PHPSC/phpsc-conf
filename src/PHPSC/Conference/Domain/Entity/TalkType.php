<?php
namespace PHPSC\Conference\Domain\Entity;

use PHPSC\Conference\Infra\Persistence\Entity;
use InvalidArgumentException;
use DateTime;

/**
 * @Entity(repositoryClass="PHPSC\Conference\Domain\Repository\TalkTypeRepository")
 * @Table("talk_type")
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class TalkType implements Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string", length=45, nullable=false)
     * @var string
     */
    private $description;

    /**
     * @Column(type="time", nullable=false)
     * @var DateTime
     */
    private $length;

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
     * @return DateTime
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param DateTime $length
     */
    public function setLength(DateTime $length)
    {
        $this->length = $length;
    }
}
