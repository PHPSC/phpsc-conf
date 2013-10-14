<?php
namespace PHPSC\Conference\UI;

use Lcobucci\DisplayObjects\Core\UIComponent;

class Sponsors extends UIComponent
{
    public function __construct()
    {
        Main::appendScript($this->getUrl('js/supporters.list.js'));
    }
}
