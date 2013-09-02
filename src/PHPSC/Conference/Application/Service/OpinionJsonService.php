<?php
namespace PHPSC\Conference\Application\Service;

use \PHPSC\Conference\Domain\Service\OpinionManagementService;
use \PHPSC\Conference\Domain\Service\TalkManagementService;

class OpinionJsonService
{
    /**
     * @var \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected $authService;

    /**
     * @var \PHPSC\Conference\Domain\Service\TalkManagementService
     */
    protected $talkManager;

    /**
     * @var \PHPSC\Conference\Domain\Service\OpinionManagementService
     */
    protected $opinionManager;

    /**
     * @param \PHPSC\Conference\Application\Service\AuthenticationService $authService
     * @param \PHPSC\Conference\Domain\Service\TalkManagementService $talkManager
     * @param \PHPSC\Conference\Domain\Service\OpinionManagementService $opinionManager
     */
    public function __construct(
        AuthenticationService $authService,
        TalkManagementService $talkManager,
        OpinionManagementService $opinionManager
    ) {
        $this->authService = $authService;
        $this->talkManager = $talkManager;
        $this->opinionManager = $opinionManager;
    }

    /**
     * @param int $talkId
     * @param string $likes
     * @throws \InvalidArgumentException
     * @return string
     */
    public function create($talkId, $likes)
    {
        try {
            $user = $this->authService->getLoggedUser();
            $talk = $this->talkManager->findById($talkId);

            if ($talk === null) {
                throw new \InvalidArgumentException('Submissão não encontrada');
            }

            $opinion = $this->opinionManager->create($user, $talk, $likes);

            $likesCount = $this->opinionManager->getLikesCount(
                $talk->getEvent(),
                $user
            );

            return json_encode(
                array(
                    'data' => array(
                        'id' => $opinion->getId(),
                        'likesCount' => $likesCount
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
                    'error' => 'Não foi possível salvar os dados na camada de persistência.'
                )
            );
        } catch (\Exception $error) {
            return json_encode(
                array(
                    'error' => 'Erro interno no processamento da requisição.'
                )
            );
        }
    }
}
