<?php
namespace PHPSC\Conference\Application\Action;

use \PHPSC\Conference\Application\View\Pages\Status as StatusView;

use \Lcobucci\ActionMapper2\Routing\Annotation\Route;
use \Lcobucci\ActionMapper2\Routing\Controller;

class Status extends Controller
{
    /**
     * @Route("/")
     */
    public function createUserForm()
    {
        if (in_array('application/json', $this->request->getAcceptableContentTypes())) {
            return json_encode($this->getStatusInscricoes());
        }

        return new StatusView();
    }

    /**
     * @return multitype:number
     */
    protected function getStatusInscricoes()
    {
        $event = $this->getEventManagement()->findCurrentEvent();

        return $this->getAttendeeManagement()->getSummary($event);
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->application->getDependencyContainer()
                                 ->get('event.management.service');
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\AttendeeManagementService
     */
    protected function getAttendeeManagement()
    {
        return $this->get('attendee.management.service');
    }
}