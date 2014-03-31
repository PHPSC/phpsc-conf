<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Controller;
use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use PHPSC\Conference\Domain\Entity\Event;
use PHPSC\Conference\Infra\UI\Component;
use PHPSC\Conference\UI\Main;
use PHPSC\Conference\UI\Pages\Call4Papers\FeedbackMessage;
use PHPSC\Conference\UI\Pages\Call4Papers\FeedbackList;
use PHPSC\Conference\UI\Pages\Call4Papers\Form;
use PHPSC\Conference\UI\Pages\Call4Papers\Index;
use PHPSC\Conference\UI\Pages\Call4Papers\Grid;

class Call4Papers extends Controller
{
    /**
     * @Route
     */
    public function renderIndex()
    {
        return new Main(new Index());
    }

    /**
     * @Route("/submissions", methods={"GET"})
     */
    public function listTalks()
    {
        return new Main(
            new Grid(
                $this->getTalkManagement()->findByUserAndEvent(
                    Component::get('user'),
                    Component::get('event')
                )
            )
        );
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
        return new Main(new Form());
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
     * @Route("/feedback", methods={"GET"})
     */
    public function feedbackList()
    {
        return new Main(
            $this->getFeedbackListFor(Component::get('event'), Component::get('user'))
        );
    }


    /**
     * @param Event $event
     * @param User $user
     * @return \Lcobucci\DisplayObjects\Core\UIComponent
     */
    protected function getFeedbackListFor(Event $event, \PHPSC\Conference\Domain\Entity\User $user)
    {
        if (!$this->getTalkManagement()->userHasAnyTalk($user, $event)
            && !$this->getAttendeeManagement()->hasAnActiveRegistration($event, $user)) {
            return new FeedbackMessage();
        }

        return new FeedbackList(
            $this->getTalkManagement()->findNonRated($event, $user),
            $this->request->getUriForPath('/call4papers/feedback'),
            $this->getOpinionManagement()->getLikesCount($event, $user)
        );
    }

    /**
     * @Route("/feedback", methods={"POST"})
     */
    public function createFeedback()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        return $this->getOpinionJsonService()->create(
            $this->request->request->get('talkId', 0),
            $this->request->request->get('likes', 1) == 1
        );
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
     * @return \PHPSC\Conference\Domain\Service\AttendeeManagementService
     */
    protected function getAttendeeManagement()
    {
        return $this->get('attendee.management.service');
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\OpinionManagementService
     */
    protected function getOpinionManagement()
    {
        return $this->get('opinion.management.service');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\OpinionJsonService
     */
    protected function getOpinionJsonService()
    {
        return $this->get('opinion.json.service');
    }

    /**
     * @return \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->get('authentication.service');
    }
}
