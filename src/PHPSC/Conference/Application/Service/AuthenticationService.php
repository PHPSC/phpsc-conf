<?php
namespace PHPSC\Conference\Application\Service;

use Lcobucci\Social\OAuth2\User as OAuth2User;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Service\UserManagementService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationService
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var UserManagementService
     */
    protected $userManager;

    /**
     * @var User
     */
    protected $loggedUser;

    /**
     * @param SessionInterface $session
     * @param UserManagementService $userManager
     */
    public function __construct(
        SessionInterface $session,
        UserManagementService $userManager
    ) {
        $this->session = $session;
        $this->userManager = $userManager;
    }

    /**
     * @param string $provider
     * @param OAuth2User $oauthUser
     * @return User
     */
    public function authenticate($provider, OAuth2User $oauthUser)
    {
        if ($user = $this->userManager->getByOAuthUser($provider, $oauthUser)) {
            return $this->saveLoggedUser($user);
        }

        if ($user = $this->userManager->create($provider, $oauthUser)) {
            return $this->saveLoggedUser($user);
        }

        $this->session->set(
            'oauth2.data',
            array(
                'provider' => $provider,
                'user' => $oauthUser
            )
        );
    }

    public function saveLoggedUser(User $user)
    {
        $this->session->set('loggedUser', $user->getId());
        $this->session->remove('oauth2.data');

        return $this->loggedUser = $user;
    }

    /**
     * @return \PHPSC\Conference\Domain\Entity\User
     */
    public function getLoggedUser()
    {
        if (!$this->isLogged() && $this->session->has('loggedUser')) {
            $this->loggedUser = $this->userManager->getById(
                $this->session->get('loggedUser')
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
