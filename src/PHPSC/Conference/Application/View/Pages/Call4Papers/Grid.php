<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

use PHPSC\Conference\Application\View\Main;
use \PHPSC\Conference\Domain\Entity\Event;
use \Lcobucci\DisplayObjects\Core\UIComponent;

class Grid extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Event
     */
    protected $event;

    /**
     * @param array $talks
     */
    public function __construct(array $talks)
    {
        $this->talks = $talks;
    }

    /**
     * @return array
     */
    public function getTalks()
    {
        return $this->talks;
    }
}