<?php
namespace PHPSC\Conference\UI\Pages\Call4Papers;

use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;

class TalkWindow extends Component
{
    /**
     * @var boolean
     */
    private $readOnly;

    /**
     * @param boolean $readOnly
     */
    public function __construct($readOnly)
    {
        $this->readOnly = $readOnly;

        Main::appendScript($this->getUrl('js/vendor/selectize.min.js'));
    }

    /**
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }
}
