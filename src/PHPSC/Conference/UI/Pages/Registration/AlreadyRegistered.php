<?php
namespace PHPSC\Conference\UI\Pages\Registration;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \PHPSC\Conference\UI\Main;
use \PHPSC\Conference\Domain\Entity\Attendee;
use PHPSC\Conference\UI\ShareButton;

class AlreadyRegistered extends UIComponent
{
    /**
     * @var \PHPSC\Conference\Domain\Entity\Attendee
     */
    protected $attendee;

    /**
     * @param \PHPSC\Conference\Domain\Entity\Attendee $attendee
     */
    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;

        Main::appendScript($this->getUrl('js/registration.resendPayment.js'));
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Event
     */
    public function getEvent()
    {
        return $this->attendee->getEvent();
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\Attendee
     */
    public function getAttendee()
    {
        return $this->attendee;
    }

    protected function getShareButton()
    {
        return new ShareButton(
            'Acabo de me inscrever no ' . $this->attendee->getEvent()->getName(),
            'Acabo de me inscrever no #phpscConf. Participe você também!',
            'http://conf.phpsc.com.br',
            'PHP_SC',
            'btn-info btn-lg',
            ''
        );
    }
}
