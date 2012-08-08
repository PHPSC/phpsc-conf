<?php
namespace PHPSC\Conference\Application\Action;

use PHPSC\Conference\Application\View\Pages\Registration\AlreadyRegistered;

use PHPSC\Conference\Application\View\Pages\Registration\Confirmation;

use \PHPSC\Conference\Application\View\Pages\Registration\Index;
use \PHPSC\Conference\Application\View\Pages\Registration\Form;
use \Lcobucci\ActionMapper2\Routing\Annotation\Route;
use \Lcobucci\ActionMapper2\Routing\Controller;
use \PHPSC\Conference\Application\View\Main;

class Registration extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function renderIndex()
    {
        $event = $this->getEventManagement()->findCurrentEvent();

        return Main::create(new Index($event), $this->application);
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function createAttendee()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getAttendeeJsonService()->create(
            $this->request->request->get('isStudent') == 'true',
            $this->request->getUriForPath('/registration/confirmation')
        );
    }

    /**
     * @Route("/new", methods={"GET"})
     */
    public function renderForm()
    {
        $user = $this->getAuthenticationService()->getLoggedUser();
        $event = $this->getEventManagement()->findCurrentEvent();
        $attendee = $this->getAttendeeManagement()->findActiveRegistration(
            $event,
            $user
        );

        if ($attendee !== null) {
            $this->redirect('/registration/registered');
        }

        return Main::create(
            new Form($user, $event, $this->getTalkManagement()),
            $this->application
        );
    }

    /**
     * @Route("/registered", methods={"GET"})
     */
    public function registered()
    {
        $user = $this->getAuthenticationService()->getLoggedUser();
        $event = $this->getEventManagement()->findCurrentEvent();
        $attendee = $this->getAttendeeManagement()->findActiveRegistration(
            $event,
            $user
        );

        if ($attendee === null) {
            $this->redirect('/registration/new');
        }

        return Main::create(
            new AlreadyRegistered($attendee),
            $this->application
        );
    }

    /**
     * @Route("/confirmation", methods={"GET"})
     */
    public function confirm()
    {
        $event = $this->getEventManagement()->findCurrentEvent();

        return Main::create(
            new Confirmation($event),
            $this->application
        );
    }

    /**
     * @Route("/share", methods={"POST"})
     */
    public function share()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getAttendeeJsonService()->share();
    }

    /**
     * @Route("/resendPayment", methods={"POST"})
     */
    public function resendPayment()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getAttendeeJsonService()->resendPayment(
            $this->request->getUriForPath('/registration/new')
        );
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\TalkManagementService
     */
    protected function getTalkManagement()
    {
        return $this->get('talk.management.service');
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\AttendeeManagementService
     */
    protected function getAttendeeManagement()
    {
        return $this->get('attendee.management.service');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\AttendeeJsonService
     */
    protected function getAttendeeJsonService()
    {
        return $this->get('attendee.json.service');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected function getAuthenticationService()
    {
    	return $this->application->getDependencyContainer()->get('authentication.service');
    }
}