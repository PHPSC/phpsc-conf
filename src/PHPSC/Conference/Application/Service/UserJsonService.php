<?php
namespace PHPSC\Conference\Application\Service;

use \PHPSC\Conference\Domain\Service\UserManagementService;
use \PHPSC\Conference\Infra\Email\DeliveryService;

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
     * @var DeliveryService
     */
    protected $deliveryService;

    /**
     * @param AuthenticationService $authService
     * @param UserManagementService $userManager
     * @param DeliveryService $deliveryService
     */
    public function __construct(
        AuthenticationService $authService,
        UserManagementService $userManager,
        DeliveryService $deliveryService
    ) {
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->deliveryService = $deliveryService;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @param boolean $follow
     * @param string $redirectTo
     * @return string
     */
    public function create(
        $name,
        $email,
        $githubUser,
        $bio,
        $redirectTo
    ) {
        $user = $this->authService->getTwitterUser();

        try {
            $user = $this->userManager->create(
                $name,
                $user->screen_name,
                $email,
                !empty($githubUser) ? $githubUser : null,
                !empty($bio) ? $bio : null
            );

            $message = $this->deliveryService->getMessageFromTemplate(
                'Welcome',
                array('name' => $name)
            );

            $message->setTo($email);

            $this->deliveryService->send($message);

            return json_encode(
                array(
                    'data' => array(
                        'id' => $user->getId(),
                        'twitterUser' => $user->getTwitterUser()
                    ),
                    'redirectTo' => $redirectTo
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

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $githubUser
     * @param string $bio
     * @return string
     */
    public function update(
        $id,
        $name,
        $email,
        $githubUser,
        $bio
    ) {
        $user = $this->authService->getLoggedUser();

        try {
            if ($user->getId() != $id) {
                throw new \InvalidArgumentException(
                    'Você não pode alterar os dados de outro usuário'
                );
            }

            $user = $this->userManager->update(
                $id,
                $name,
                $email,
                !empty($githubUser) ? $githubUser : null,
                !empty($bio) ? $bio : null
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
