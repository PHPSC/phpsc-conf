<?php
namespace PHPSC\Conference\Application\Action\Evaluations;

use Lcobucci\ActionMapper2\Errors\ForbiddenException;
use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Application\Service\AuthenticationService;
use PHPSC\Conference\Domain\Entity\Talk;
use PHPSC\Conference\Domain\Entity\User;
use PHPSC\Conference\Domain\Service\TalkEvaluation\Locator;
use PHPSC\Conference\Domain\Service\TalkManagementService;
use PHPSC\Conference\Domain\Service\UserManagementService;
use PHPSC\Conference\Domain\Service\TalkEvaluation\Manager;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Evaluations extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function jsonList()
    {
        //TODO refactor this
        $list = array();
        $talk = $this->getTalk();
        $evaluator = $this->getEvaluator();

        foreach ($this->getEvaluationsLocator()->search($talk, $evaluator) as $evaluation) {
            $list[] = $evaluation->jsonSerialize();
        }

        $this->response->setContentType('application/json');

        return json_encode($list);
    }

    /**
     * @Route("/", methods={"POST"}, contentType={"application/json"})
     */
    public function create()
    {
        //TODO refactor this
        $talk = $this->getTalkManagement()->findById($this->request->request->get('talk'));
        $evaluator = $this->getAuthenticationService()->getLoggedUser();

        $evaluation = $this->getEvaluationsManager()->create(
            $talk,
            $evaluator,
            $this->request->request->get('originality'),
            $this->request->request->get('relevance'),
            $this->request->request->get('quality'),
            $this->request->request->get('notes')
        );

        $this->response->setContentType('application/json');
        $this->response->setStatusCode(201);

        $this->response->headers->set(
            'Location',
            $this->request->getUriForPath('/evaluation/' . $evaluation->getId())
        );

        return json_encode($evaluation->jsonSerialize());
    }

    /**
     * @return Talk
     */
    protected function getTalk()
    {
        $talkId = $this->request->query->get('talk');

        if ($talkId === null) {
            return null;
        }

        return $this->getTalkManagement()->findById($talkId);
    }

    /**
     * @return User
     */
    protected function getEvaluator()
    {
        $evaluator = $this->request->query->get('evaluator');

        if ($evaluator === null) {
            return null;
        }

        if ($evaluator == 0) {
            $user = $this->getAuthenticationService()->getLoggedUser();

            if ($user === null) {
                throw new ForbiddenException('Você deve ser um avaliador');
            }

            return $user;
        }

        return $this->getUserManagement()->getById($evaluator);
    }

    /**
     * @return TalkManagementService
     */
    protected function getTalkManagement()
    {
        return $this->get('talk.management.service');
    }

    /**
     * @return UserManagementService
     */
    protected function getUserManagement()
    {
        return $this->get('user.management.service');
    }

    /**
     * @return Locator
     */
    protected function getEvaluationsLocator()
    {
        return $this->get('talkEvaluation.locator');
    }

    /**
     * @return Manager
     */
    protected function getEvaluationsManager()
    {
        return $this->get('talkEvaluation.manager');
    }

    /**
     * @return AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->get('authentication.service');
    }
}
