<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Entity\Supporter as SupporterEntity;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Supporter extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"image/*"})
     * @param int $id
     */
    public function displayLogo($id)
    {
        /* @var $supporter SupporterEntity */
        $supporter = $this->get('supporter.management.service')->findById($id);

        return $this->get('image.rendering.service')->resize(
            $supporter->getCompany()->getLogo(),
            $this->request->query->get('w'),
            $this->request->query->get('h'),
            $this->request,
            $this->response
        );
    }
}
