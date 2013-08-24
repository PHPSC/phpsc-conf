<?php
namespace PHPSC\Conference\Application\Service;

use \PHPSC\Conference\Domain\Service\EventManagementService;
use \PHPSC\Conference\Domain\Service\TalkManagementService;

class TalkJsonService
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
     * @var \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected $eventManager;

    /**
     * @param \PHPSC\Conference\Application\Service\AuthenticationService $authService
     * @param \PHPSC\Conference\Domain\Service\TalkManagementService $talkManager
     * @param \PHPSC\Conference\Domain\Service\EventManagementService $eventManager
     */
    public function __construct(
        AuthenticationService $authService,
        TalkManagementService $talkManager,
        EventManagementService $eventManager
    ) {
        $this->authService = $authService;
        $this->talkManager = $talkManager;
        $this->eventManager = $eventManager;
    }

    /**
     * @param string $title
     * @param string $typeId
     * @param string $shortDescription
     * @param string $longDescription
     * @param string $complexity
     * @param string $tags
     * @return string
     */
    public function create(
        $title,
        $typeId,
        $shortDescription,
        $longDescription,
        $complexity,
        $tags
    ) {
        $event = $this->eventManager->findCurrentEvent();
        $speaker = $this->authService->getLoggedUser();

        try {
            $talk = $this->talkManager->create(
                $event,
                $speaker,
                (int) $typeId,
                $title,
                $shortDescription,
                $longDescription,
                $complexity,
                !empty($tags) ? $tags : null
            );

            return json_encode(
                array(
                    'data' => array(
                        'id' => $talk->getId(),
                        'title' => $talk->getTitle()
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

    /**
     * @param int $id
     * @param string $title
     * @param string $typeId
     * @param string $shortDescription
     * @param string $longDescription
     * @param string $complexity
     * @param string $tags
     * @return string
     */
    public function update(
        $id,
        $title,
        $typeId,
        $shortDescription,
        $longDescription,
        $complexity,
        $tags
    ) {
        $speaker = $this->authService->getLoggedUser();

        try {
            $talk = $this->talkManager->update(
                $id,
                $speaker,
                (int) $typeId,
                $title,
                $shortDescription,
                $longDescription,
                $complexity,
                !empty($tags) ? $tags : null
            );

            return json_encode(
                array(
                    'data' => array(
                        'id' => $talk->getId(),
                        'title' => $talk->getTitle()
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

    /**
     * @param int $id
     * @param boolean $verifyOwnership
     * @return string
     */
    public function getById($id, $verifyOwnership = true)
    {
        $talk = $this->talkManager->findById(
            $id,
            $verifyOwnership ? $this->authService->getLoggedUser() : null
        );

        if ($talk === null) {
            $response = array(
                'error' => 'Não foi possível encontrar a submissão de trabalho.'
            );
        } else {
            $response = array(
                'id' => $talk->getId(),
                'title' => $talk->getTitle(),
                'type' => array(
                    'id' => $talk->getType()->getId(),
                    'description' => $talk->getType()->getDescription()
                ),
                'shortDescription' => $talk->getShortDescription(),
                'longDescription' => $talk->getLongDescription(),
                'complexity' => $talk->getComplexity(),
                'tags' => explode(',', $talk->getTags()),
                'approved' => $talk->getApproved(),
                'creationTime' => $talk->getCreationTime()->format(\DateTime::RFC3339)
            );
        }

        return json_encode($response);
    }
}
