<?php
namespace PHPSC\Conference\UI\Pages\Call4Papers;

use PHPSC\Conference\UI\Main;
use \Lcobucci\DisplayObjects\Core\UIComponent;

class TalkWindow extends UIComponent
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
