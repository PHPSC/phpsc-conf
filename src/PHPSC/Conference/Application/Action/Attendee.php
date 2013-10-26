<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Entity\Attendee as AttendeeEntity;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Pages\User\Form;
use PHPSC\Conference\Domain\Service\AttendeeManagementService;

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


        /**
         * @TODO fazer o update
         */

        $this->response->setContentType('application/json');

        return json_encode($evaluation->jsonSerialize());
    }


    /**
     * @return AttendeeManagementService
     */
    protected function getAttendeeManagement()
    {
        return $this->get('attendee.management.service');
    }
}