<?php
namespace PHPSC\Conference\Application\Service;

use InvalidArgumentException;
use PDOException;
use Exception;
use PHPSC\Conference\Infra\Email\DeliveryService;
use PHPSC\Conference\Domain\Service\UserManagementService;

class UserJsonService
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var UserManagementService
     */
    protected $userManager;

    /**
     * @param AuthenticationService $authService
     * @param UserManagementService $userManager
     */
    public function __construct(
        AuthenticationService $authService,
        UserManagementService $userManager
    ) {
        $this->authService = $authService;
        $this->userManager = $userManager;
    }

    /**
     * @param array $oauthData
     * @param string $name
     * @param string $email
     * @param string $bio
     * @param string $redirectTo
     * @return string
     */
    public function create(
        array $oauthData,
        $name,
        $email,
        $bio,
        $redirectTo
    ) {
        try {
            $user = $this->userManager->create(
                $oauthData['provider'],
                $oauthData['user'],
                $name,
                $email,
                !empty($bio) ? $bio : null
            );

            $this->authService->saveLoggedUser($user);

            return json_encode(
                array(
                    'data' => array(
                        'id' => $user->getId(),
                        'username' => $user->getDefaultProfile()->getUsername()
                    ),
                    'redirectTo' => $redirectTo
                )
            );
        } catch (InvalidArgumentException $error) {
            return json_encode(
                array(
                    'error' => $error->getMessage()
                )
            );
        } catch (PDOException $error) {
            return json_encode(
                array(
                    'error' => 'Não foi possível salvar os dados na camada de persistência'
                )
            );
        } catch (Exception $error) {
            return json_encode(
                array(
                    'error' => 'Erro interno no processamento da requisição'
                )
            );
        }
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $bio
     * @return string
     */
    public function update(
        $id,
        $name,
        $email,
        $bio
    ) {
        $user = $this->authService->getLoggedUser();

        try {
            if ($user->getId() != $id) {
                throw new InvalidArgumentException(
                    'Você não pode alterar os dados de outro usuário'
                );
            }

            $user = $this->userManager->update(
                $id,
                $name,
                $email,
                !empty($bio) ? $bio : null
            );

            return json_encode(
                array(
                    'data' => array(
                        'id' => $user->getId(),
                        'username' => $user->getDefaultProfile()->getUsername()
                    )
                )
            );
        } catch (InvalidArgumentException $error) {
            return json_encode(
                array(
                    'error' => $error->getMessage()
                )
            );
        } catch (PDOException $error) {
            return json_encode(
                array(
                    'error' => 'Não foi possível salvar os dados na camada de persistência'
                )
            );
        } catch (Exception $error) {
            return json_encode(
                array(
                    'error' => 'Erro interno no processamento da requisição'
                )
            );
        }
    }
}
