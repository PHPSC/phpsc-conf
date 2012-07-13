<?php
namespace PHPSC\Conference\Application\Action;

use \Lcobucci\ActionMapper2\Routing\Filter;

class UserFilter extends Filter
{
    /**
     * @see \Lcobucci\ActionMapper2\Routing\Filter::process()
     */
    public function process()
    {
        $service = $this->getAuthenticationService();

        if ($service->getTwitterUser() === null) {
            $this->application->redirect('/');
        }

        if ($this->request->getRequestedPath() == '/user/new'
            && $service->getLoggedUser()) {
            $this->application->redirect('/');
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