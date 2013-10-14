<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Application\Service\SupporterJsonService;
use PHPSC\Conference\Domain\Service\EventManagementService;

class Supporters extends Controller
{
    /**
     * @Route("/", methods={"POST"}, contentType={"application/json"})
     */
    public function create()
    {
        $event = $this->getEventManager()->findCurrentEvent();

        $supporter = $this->getSupporterService()->create(
            $event,
            $this->request->request->get('socialId'),
            $this->request->request->get('name'),
            $this->request->request->get('email'),
            $this->request->request->get('phone'),
            $this->request->request->get('website'),
            $this->request->request->get('twitterId'),
            $this->request->request->get('fanpage'),
            $this->request->request->get('details'),
            $this->request->files->get('logo')
        );

        $this->response->setStatusCode(201);
        $this->response->headers->set(
            'Location',
            $this->request->getUriForPath('/supporter/' . $supporter['id'])
        );

        return json_encode($supporter);
    }

    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function showSupporters()
    {
        $event = $this->getEventManager()->findCurrentEvent();
        $supporters = $this->getSupporterService()->findByEvent($event);

        return json_encode($supporters);
    }

    /**
     * @return SupporterJsonService
     */
    protected function getSupporterService()
    {
        return $this->get('supporter.json.service');
    }

    /**
     * @return EventManagementService
     */
    protected function getEventManager()
    {
        return $this->get('event.management.service');
    }
}
