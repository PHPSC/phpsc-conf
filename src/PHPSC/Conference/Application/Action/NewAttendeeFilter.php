<?php
namespace PHPSC\Conference\Application\Action;

use \Lcobucci\ActionMapper2\Routing\Filter;

class NewAttendeeFilter extends Filter
{
    /**
     * @see \Lcobucci\ActionMapper2\Routing\Filter::process()
     */
    public function process()
    {
        if ($this->getAuthenticationService()->getTwitterUser() === null) {
            $this->request->getSession()->set(
                'redirectTo',
                '/registration/new'
            );

            $this->application->redirect('/oauth/redirect');
        }

        if ($this->getAuthenticationService()->getLoggedUser() === null) {
            $this->request->getSession()->set(
                'redirectTo',
                '/registration/new'
            );

            $this->application->redirect('/user/new');
        }

        $event = $this->getEventManagement()->findCurrentEvent();

        if (!$event->isRegistrationInterval(new \DateTime())) {
            $this->application->redirect('/registration');
        }
    }

    /**
     * @return \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->application->getDependencyContainer()
                                 ->get('authentication.service');
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