<?php
namespace PHPSC\Conference\Application\Service;

use \PHPSC\Conference\Domain\Service\UserManagementService;
use \Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationService
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var \PHPSC\Conference\Domain\Service\UserManagementService
     */
    protected $userManager;

    /**
     * @var \PHPSC\Conference\Domain\Entity\User
     */
    protected $loggedUser;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \PHPSC\Conference\Domain\Service\UserManagementService $userManager
     */
    public function __construct(
        SessionInterface $session,
        UserManagementService $userManager
    ) {
        $this->session = $session;
        $this->userManager = $userManager;
    }

    /**
     * @return \stdClass
     */
    public function getTwitterUser()
    {
        /*if (!$this->session->has('twitterUser')) {
            if ($data = $this->provider->getLoggedUser()) {
                $this->session->set('twitterUser', $data);
            }
        }

        return $this->session->get('twitterUser');*/
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getLoggedUser()
    {
        if (!$this->isLogged() && $twitterUser = $this->getTwitterUser()) {
            $this->loggedUser = $this->userManager->getByTwitterUser(
                $twitterUser->screen_name
            );
        }

        return $this->loggedUser;
    }

    /**
     * @return boolean
     */
    public function isLogged()
    {
        return $this->loggedUser !== null;
    }
}
