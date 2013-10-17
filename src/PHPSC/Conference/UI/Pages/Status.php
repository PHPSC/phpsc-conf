<?php
namespace PHPSC\Conference\UI\Pages;

use Lcobucci\DisplayObjects\Core\UIComponent;
use PHPSC\Conference\UI\Sponsors;

class Status extends UIComponent
{
    /**
     * @return Sponsors
     */
    protected function getSponsors()
    {
        return new Sponsors();
    }
}
