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

        if ($service->getTwitterUser() === null) {
            $this->application->redirect('/');
        }

        if ($this->request->getRequestedPath() == '/user/new'
            && $service->getLoggedUser()) {
            $this->application->redirect('/');
        }
    }
}
