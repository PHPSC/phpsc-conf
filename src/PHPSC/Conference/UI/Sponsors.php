<?php
namespace PHPSC\Conference\UI;

use PHPSC\Conference\Infra\UI\Component;

class Sponsors extends Component
{
    public function __construct()
    {
        Main::appendScript($this->getUrl('js/supporters.list.js'));
        Main::appendScript($this->getUrl('js/sponsors.list.js'));
    }
}
