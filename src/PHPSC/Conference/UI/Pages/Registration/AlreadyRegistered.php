<?php
namespace PHPSC\Conference\UI\Pages\Registration;

use PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\ShareButton;

class AlreadyRegistered extends Component
{
    /**
     * @var Attendee
     */
    protected $attendee;

    /**
     * @param Attendee $attendee
     */
    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;

        Main::appendScript($this->getUrl('js/registration.resendPayment.js'));
    }

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
