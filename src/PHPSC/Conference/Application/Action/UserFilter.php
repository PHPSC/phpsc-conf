<?php
namespace PHPSC\Conference\Application\Action;

class UserFilter extends BasicFilter
{
    /**
     * @see \Lcobucci\ActionMapper2\Routing\Filter::process()
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
    }
}
