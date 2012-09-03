<?php
namespace PHPSC\Conference\Application\View\Pages\Call4Papers;

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
    }

    /**
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }
}