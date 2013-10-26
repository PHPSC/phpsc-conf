<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Errors\PageNotFoundException;
use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\AttendeeManagementService;
use PHPSC\Conference\Domain\Service\AttendeeCredentialingService;

class Attendee extends Controller
{
    /**
     * @Route("/", methods={"PUT"}, contentType={"application/json"})
     */
    public function update($id)
    {
        $attendee = $this->getAttendeeManagement()->findById($id);

        if ($attendee === null) {
            throw new PageNotFoundException('Inscrição não encontrada');
        }

        if ($this->request->request->get('status') == '3') {
            $this->getCredentialingService()->confirm($attendee);
        } elseif ($this->request->request->get('status') == '2') {
            $this->getCredentialingService()->registerPayment($attendee);
        }

        $this->response->setContentType('application/json');

        return json_encode($attendee->jsonSerialize());
    }

    /**
     *
     * @return AttendeeManagementService
     */
    protected function getAttendeeManagement()
    {
        return $this->get('attendee.management.service');
    }

    /**
     *
     * @return AttendeeCredentialingService
     */
    protected function getCredentialingService()
    {
        return $this->get('attendee.credentialing.service');
    }
}
