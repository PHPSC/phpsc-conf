<?php
namespace PHPSC\Conference\Application\Filter;

class UserFilter extends BasicFilter
{
    /**
     * {@inheritdoc}
     */
    public function process()
    {
        $service = $this->getAuthenticationService();

        if ($this->request->getRequestedPath() == '/user/new'
            && $service->getLoggedUser()) {
            $this->application->redirect('/');
        }

        if ($this->request->getRequestedPath() == '/user/new'
            && !$this->request->getSession()->has('oauth2.data')) {
            $this->application->redirect('/oauth');
        }

        $this->validateUserAuthentication();
    }
}
