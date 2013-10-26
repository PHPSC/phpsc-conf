<?php
namespace PHPSC\Conference\Application\Action\Admin;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\EventManagementService;
use PHPSC\Conference\Domain\Service\AttendeeManagementService;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Admin\Credentialing\Grid;

class Credentialing extends Controller
{
    /**
     * @Route("/")
     */
    public function showList()
    {
        $event = $this->getEventManagement()->findCurrentEvent();
        $attendees = $this->getAttendeeManagement()->findByEvent($event);

        return Main::create(new Grid($event, $attendees), $this->application);
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return AttendeeManagementService
     */
    protected function getAttendeeManagement()
    {
        return $this->get('attendee.management.service');
    }
}
