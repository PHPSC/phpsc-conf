<?php
namespace PHPSC\Conference\Application\Filter;

use Lcobucci\ActionMapper2\Routing\Filter;
use PHPSC\Conference\Application\Service\AuthenticationService;

abstract class BasicFilter extends Filter
{
    /**
     * Verify if the user is logged, if not redirect to login page
     */
    protected function validateUserRegistration()
    {
        if ($this->getAuthenticationService()->getLoggedUser() === null) {
            $this->request->getSession()->set(
                'redirectTo',
                $this->request->getRequestedPath()
            );

            $this->application->redirect('/oauth');
        }
    }

    /**
     * @return AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->get('authentication.service');
    }
}
