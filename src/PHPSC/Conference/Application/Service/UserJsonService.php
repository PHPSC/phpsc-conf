<?php
namespace PHPSC\Conference\Application\Service;

use \PHPSC\Conference\Domain\Service\UserManagementService;

class UserJsonService
{
    /**
     * @var \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected $authService;

    /**
     * @var \PHPSC\Conference\Domain\Service\UserManagementService
     */
    protected $userManager;

    /**
     * @param \PHPSC\Conference\Application\Service\AuthenticationService $authService
     * @param \PHPSC\Conference\Domain\Service\UserManagementService $userManager
     */
    public function __construct(
        AuthenticationService $authService,
        UserManagementService $userManager
    ) {
        $this->authService = $authService;
        $this->userManager = $userManager;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @param boolean $follow
     * @return string
     */
    public function create($name, $email, $githubUser, $bio, $follow)
    {
        $user = $this->authService->getTwitterUser();

        try {
            $user = $this->userManager->create(
                $name,
                $user->screen_name,
                $email,
                !empty($githubUser) ? $githubUser : null,
                !empty($bio) ? $bio : null,
                $follow
            );

            return json_encode(
                array(
                    'data' => array(
                        'id' => $user->getId(),
                        'twitterUser' => $user->getTwitterUser()
                    )
                )
            );
        } catch (\InvalidArgumentException $error) {
            return json_encode(
                array(
                    'error' => $error->getMessage()
                )
            );
        } catch (\PDOException $error) {
            return json_encode(
                array(
                    'error' => 'Não foi possível salvar os dados na camada de persistência'
                )
            );
        } catch (\Exception $error) {
            return json_encode(
                array(
                    'error' => 'Erro interno no processamento da requisição'
                )
            );
        }
    }
}