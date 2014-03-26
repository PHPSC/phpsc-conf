<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Entity\Event as EventEntity;
use Lcobucci\ActionMapper2\Errors\PageNotFoundException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Event extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"image/*"})
     * @param int $id
     */
    public function displayLogo($id)
    {
        /* @var $event EventEntity */
        $event = $this->get('event.management.service')->findById($id);

        if (!$event->hasLogo()) {
            throw new PageNotFoundException('This event dont have a logo');
        }

        return $this->get('image.rendering.service')->resize(
            $event->getLogo(),
            $this->request->query->get('w'),
            $this->request->query->get('h'),
            $this->request,
            $this->response
        );
    }
}
