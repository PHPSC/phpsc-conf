<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Pages\Auth;

class OAuth extends Controller
{
    public function choose()
    {
        return Main::create(new Auth(), $this->application);
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function redirectToProvider($provider)
    {
        $client = $this->get('oauth2.manager');
        $session = $this->request->getSession();

        $session->set('oauth.state', uniqid());

        $this->redirect(
            $client->getAuthorizationUri(
                $provider,
                array(),
                $session->get('oauth.state')
            )
        );
    }

    /**
     * @Route("/callback")
     */
    public function receiveProviderData($provider)
    {
        $session = $this->request->getSession();

        if ($session->get('oauth.state') != $this->request->get('state')) {
            $this->redirect('/');
        }

        $client = $this->get('oauth2.manager');
        $user = $client->getAuthenticatedUser($provider, $this->request->query);

        if (!$this->get('authentication.service')->authenticate($provider, $user)) {
            $this->redirect('/user/new');
        }

        $path = $session->get('redirectTo', '/');

        if ($path != '/') {
            $session->remove('redirectTo');
        }

        $this->redirect($path);
    }
}
