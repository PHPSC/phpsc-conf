<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

use PHPSC\Conference\Application\View\Main;

use \PHPSC\Conference\Domain\Entity\Talk;
use \Lcobucci\DisplayObjects\Core\UIComponent;

class Form extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Talk
     */
    protected $talk;

    /**
     * @param \PHPSC\Conference\Domain\Entity\Talk $talk
     */
    public function __construct(Talk $talk = null)
    {
        $this->talk = $talk ?: new Talk();

        Main::appendScript($this->getBaseUrl() . '/js/talk.create.js');
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Talk
     */
    public function getTalk()
    {
        return $this->talk;
    }
}