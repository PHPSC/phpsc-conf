<?php
namespace PHPSC\Conference\Domain\Entity;

use DateTime;
use InvalidArgumentException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <luis@phpsc.com.br>
 * @Entity
 * @Table("logo")
 */
class Logo
{
    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @Column(type="blob")
     * @var resource
     */
    private $image;

    /**
     * @Column(type="datetime", name="created_at")
     * @var DateTime
     */
    private $createdAt;

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
     * @return resource
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param resource $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return 'image/png';
    }
}
