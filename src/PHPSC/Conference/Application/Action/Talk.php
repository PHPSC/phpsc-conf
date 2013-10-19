<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Service\TalkManagementService;

class Talk extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"application/json"})
     */
    public function renderJson($id)
    {
        $talk = $this->getTalkManagement()->findById($id);

        $data = array(
        	'id' => $talk->getId(),
            'speakers' => array(),
            'title' => $talk->getTitle(),
            'type' => $talk->getType()->getDescription(),
            'shortDescription' => $talk->getShortDescription(),
            'longDescription' => $talk->getLongDescription(),
            'tags' => explode(',', $talk->getTags())
        );

        foreach ($talk->getSpeakers() as $speaker) {
            $data['speakers'][] = array(
            	'id' => $speaker->getId(),
            	'name' => $speaker->getName(),
                'avatar' => $speaker->getDefaultProfile()->getAvatar()
            );
        }

        $this->response->setContentType('application/json');

        return json_encode($data);
    }

    /**
     * @return TalkManagementService
     */
    protected function getTalkManagement()
    {
        return $this->get('talk.management.service');
    }
}
