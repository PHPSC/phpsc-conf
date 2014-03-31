<?php
namespace PHPSC\Conference\UI\Pages\Call4Papers;

use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;

class Form extends Component
{
    /**
     * @var Talk
     */
    protected $talk;

    /**
     * @param Talk $talk
     */
    public function __construct(Talk $talk = null)
    {
        $this->talk = $talk ?: new Talk();

        Main::appendScript($this->getUrl('js/talk.create.js'));
        Main::appendScript($this->getUrl('js/vendor/selectize.min.js'));
    }

    /**
     * @return Talk
     */
    public function getTalk()
    {
        return $this->talk;
    }
}
