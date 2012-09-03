<?php
namespace PHPSC\Conference\Application\Action;

use \PHPSC\Conference\Application\View\Pages\Call4Papers\Form;
use \PHPSC\Conference\Application\View\Pages\Call4Papers\Index;
use \PHPSC\Conference\Application\View\Pages\Call4Papers\Grid;
use \PHPSC\Conference\Application\View\Main;

use \Lcobucci\ActionMapper2\Routing\Controller;
use \Lcobucci\ActionMapper2\Routing\Annotation\Route;

class Call4Papers extends Controller
{
    /**
     * @Route
     */
    public function renderIndex()
    {
        $event = $this->getEventManagement()->findCurrentEvent();

        return Main::create(new Index($event), $this->application);
    }

    /**
     * @Route("/submissions", methods={"GET"})
     */
    public function listTalks()
    {
    	$user = $this->getAuthenticationService()->getLoggedUser();
    	$event = $this->getEventManagement()->findCurrentEvent();
    	$talks = $this->getTalkManagement()->findByUserAndEvent($user, $event);

    	return Main::create(new Grid($event, $talks), $this->application);
    }

    /**
     * @Route("/submissions", methods={"POST"})
     */
    public function createTalk()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getTalkJsonService()->create(
            $this->request->request->get('title'),
            $this->request->request->get('type'),
            $this->request->request->get('shortDescription'),
            $this->request->request->get('longDescription'),
            $this->request->request->get('complexity'),
            $this->request->request->get('tags')
        );
    }

    /**
     * @Route("/submissions/new")
     */
    public function talkForm()
    {
        return Main::create(new Form(), $this->application);
    }

    /**
     * @Route("/submissions/(id)", methods={"GET"}, requirements={"[\d]{1,}"})
     */
    public function talkInfo($id)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getTalkJsonService()->getById($id);
    }

    /**
     * @Route("/submissions/(id)", methods={"PUT"}, requirements={"[\d]{1,}"})
     */
    public function updateTalk($id)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getTalkJsonService()->update(
            $id,
            $this->request->request->get('title'),
            $this->request->request->get('type'),
            $this->request->request->get('shortDescription'),
            $this->request->request->get('longDescription'),
            $this->request->request->get('complexity'),
            $this->request->request->get('tags')
        );
    }

    /**
     * @Route("/submissions/share", methods={"POST"})
     */
    public function share()
    {
        $user = $this->getAuthenticationService()->getLoggedUser();
        $event = $this->getEventManagement()->findCurrentEvent();
        $talks = $this->getTalkManagement()->findByUserAndEvent($user, $event);

        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getTalkJsonService()->share(count($talks));
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->get('event.management.service');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\TalkJsonService
     */
    protected function getTalkJsonService()
    {
        return $this->get('talk.json.service');
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\TalkManagementService
     */
    protected function getTalkManagement()
    {
    	return $this->get('talk.management.service');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected function getAuthenticationService()
    {
    	return $this->application->getDependencyContainer()->get('authentication.service');
    }
}