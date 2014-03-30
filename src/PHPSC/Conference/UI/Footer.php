<?php
namespace PHPSC\Conference\UI;

use PHPSC\Conference\Infra\UI\Component;

class Footer extends Component
{
    /**
     * @return \PHPSC\Conference\UI\Sponsors
     */
    public function renderSponsors()
    {
        return new Sponsors();
    }
}
