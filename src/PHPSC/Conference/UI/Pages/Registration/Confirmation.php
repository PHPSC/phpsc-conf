<?php
namespace PHPSC\Conference\UI\Pages\Registration;

use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\ShareButton;

class Confirmation extends Component
{
    /**
     * @return ShareButton
     */
    protected function getShareButton()
    {
        return new ShareButton(
            'Acabo de me inscrever no ' . $this->event->getName(),
            'Acabo de me inscrever no #phpscConf. Participe você também!',
            'http://conf.phpsc.com.br',
            'PHP_SC',
            'btn-info btn-lg',
            ''
        );
    }
}
