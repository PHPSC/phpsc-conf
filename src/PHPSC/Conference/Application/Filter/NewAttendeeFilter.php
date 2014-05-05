<?php
namespace PHPSC\Conference\Application\Filter;

use DateTime;
use PHPSC\Conference\Domain\Service\EventManagementService;

class NewAttendeeFilter extends BasicFilter
{
    /**
     * {@inheritdoc}
     */
    public function process()
    {
        $this->validateUserAuthentication();

        $event = $this->getEventManagement()->findCurrentEvent();

        if (!$event->isRegistrationPeriod(new DateTime())) {
            $this->application->redirect('/registration');
        }
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }
}
