<?php
namespace PHPSC\Conference\Application\Action\Evaluations;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\TalkEvaluation\Locator;
use PHPSC\Conference\Domain\Service\TalkEvaluation\Manager;
use Lcobucci\ActionMapper2\Errors\PageNotFoundException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Evaluation extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function show($id)
    {
        //TODO refactor this
        $evaluation = $this->getEvaluationsLocator()->getById($id);

        if ($evaluation === null) {
            throw new PageNotFoundException('Avaliação não encontrada');
        }

        $this->response->setContentType('application/json');

        return json_encode($evaluation->jsonSerialize());
    }

    /**
     * @Route("/", methods={"PUT"}, contentType={"application/json"})
     */
    public function update($id)
    {
        //TODO refactor this
        $evaluation = $this->getEvaluationsLocator()->getById($id);

        if ($evaluation === null) {
            throw new PageNotFoundException('Avaliação não encontrada');
        }

        $this->getEvaluationsManager()->update(
            $evaluation,
            $this->request->request->get('originality'),
            $this->request->request->get('relevance'),
            $this->request->request->get('quality'),
            $this->request->request->get('notes')
        );

        $this->response->setContentType('application/json');

        return json_encode($evaluation->jsonSerialize());
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
}
