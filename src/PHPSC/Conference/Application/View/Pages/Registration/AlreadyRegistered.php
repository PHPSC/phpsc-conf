<?php
namespace PHPSC\Conference\Application\View\Pages\Registration;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \PHPSC\Conference\Application\View\Main;
use \PHPSC\Conference\Domain\Entity\Attendee;

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

        Main::appendScript($this->getBaseUrl() . '/js/registration.share.js');
        Main::appendScript($this->getBaseUrl() . '/js/registration.resendPayment.js');
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
}