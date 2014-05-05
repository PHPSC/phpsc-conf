<?php
namespace PHPSC\Conference\Application\Action;

use PHPSC\Conference\UI\Pages\Registration\AlreadyRegistered;

use PHPSC\Conference\UI\Pages\Registration\Confirmation;

use \PHPSC\Conference\UI\Pages\Registration\Index;
use \PHPSC\Conference\UI\Pages\Registration\Form;
use \Lcobucci\ActionMapper2\Routing\Annotation\Route;
use \Lcobucci\ActionMapper2\Routing\Controller;
use \PHPSC\Conference\UI\Main;
use PHPSC\Conference\Infra\UI\Component;

class Registration extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function renderIndex()
    {
        return new Main(new Index());
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function createAttendee()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getAttendeeJsonService()->create(
            $this->request->request->get('isStudent') == 'true',
            $this->request->request->get('discountCoupon'),
            $this->request->getUriForPath('/registration/confirmation')
        );
    }

    /**
     * @Route("/new", methods={"GET"})
     */
    public function renderForm()
    {
        $attendee = $this->getAttendeeManagement()->findActiveRegistration(
            Component::get('event'),
            Component::get('user')
        );

        if ($attendee !== null) {
            $this->redirect('/registration/registered');
        }

        return new Main(
            new Form(
                $this->getTalkManagement(),
                $this->get('attendee.cost.calculator')
            )
        );
    }

    /**
     * @Route("/registered", methods={"GET"})
     */
    public function registered()
    {
        $attendee = $this->getAttendeeManagement()->findActiveRegistration(
            Component::get('event'),
            Component::get('user')
        );

        if ($attendee === null) {
            $this->redirect('/registration/new');
        }

        return new Main(new AlreadyRegistered($attendee));
    }

    /**
     * @Route("/confirmation", methods={"GET"})
     */
    public function confirm()
    {
        return new Main(new Confirmation());
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
}
