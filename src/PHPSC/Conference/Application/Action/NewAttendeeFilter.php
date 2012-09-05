<?php
namespace PHPSC\Conference\Application\Action;

class NewAttendeeFilter extends BasicFilter
{
    /**
     * @see \Lcobucci\ActionMapper2\Routing\Filter::process()
     */
    public function process()
    {
        $this->validateTwitterSession();
        $this->validateUserRegistration();

        $event = $this->getEventManagement()->findCurrentEvent();

        if (!$event->isRegistrationInterval(new \DateTime())) {
            $this->application->redirect('/registration');
        }
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->application->getDependencyContainer()
                                 ->get('event.management.service');
    }
}