<?php
namespace PHPSC\Conference\UI\Pages;

use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Sponsors;

class Status extends Component
{
    /**
     * @return Sponsors
     */
    protected function getSponsors()
    {
        return new Sponsors();
    }
}
