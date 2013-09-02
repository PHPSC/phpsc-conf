<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\UI\Pages\Status as StatusView;

class Status extends Controller
{
    /**
     * @Route("/", contentType={"application/json"})
     */
    public function getStatus()
    {
        $this->response->setContentType('application/json');

        return json_encode($this->getStatusInscricoes());
    }

    /**
     * @Route("/")
     */
    public function createUserForm()
    {
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
