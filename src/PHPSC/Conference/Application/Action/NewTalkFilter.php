<?php
namespace PHPSC\Conference\Application\Action;

use \Lcobucci\ActionMapper2\Routing\Filter;

class NewTalkFilter extends Filter
{
    /**
     * @see \Lcobucci\ActionMapper2\Routing\Filter::process()
     */
    public function process()
    {
        if ($this->getAuthenticationService()->getLoggedUser() === null) {
            $this->request->getSession()->set(
                'redirectTo',
                '/call4papers/submissions/new'
            );

            $this->application->redirect('/oauth/redirect');
        }

        $event = $this->getEventManagement()->findCurrentEvent();

        if (!$event->isSubmissionsInterval(new \DateTime())) {
            $this->application->redirect('/call4papers');
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