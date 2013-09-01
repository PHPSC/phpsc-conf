<?php
namespace PHPSC\Conference\UI;

use \Lcobucci\DisplayObjects\Core\UIComponent;

class Footer extends UIComponent
{
	/**
	 * @return \PHPSC\Conference\UI\Sponsors
	 */
	public function renderSponsors()
	{
		return new Sponsors();
	}
}
