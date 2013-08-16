<?php
namespace PHPSC\Conference\Application\Action;

use \Lcobucci\ActionMapper2\Routing\Filter;

abstract class BasicFilter extends Filter
{
    protected function validateTwitterSession()
    {
        if ($this->getAuthenticationService()->getTwitterUser() === null) {
            $this->request->getSession()->set(
                'redirectTo',
                $this->request->getRequestedPath()
            );

            $this->application->redirect('/oauth/redirect');
        }
    }

    protected function validateUserRegistration()
    {
        if ($this->getAuthenticationService()->getLoggedUser() === null) {
            $this->request->getSession()->set(
                'redirectTo',
                $this->request->getRequestedPath()
            );

            $this->application->redirect('/user/new');
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
}
