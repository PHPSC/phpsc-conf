<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Entity\Location as LocationEntity;
use Lcobucci\ActionMapper2\Errors\PageNotFoundException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Location extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"image/*"})
     * @param int $id
     */
    public function displayLogo($id)
    {
        /* @var $location LocationEntity */
        $location = $this->get('location.locator')->findById($id);

        if (!$location->hasLogo()) {
            throw new PageNotFoundException('Localização sem logotipo');
        }

        return $this->get('image.rendering.service')->resize(
            $location->getLogo(),
            $this->request->query->get('w'),
            $this->request->query->get('h'),
            $this->request,
            $this->response
        );
    }
}
