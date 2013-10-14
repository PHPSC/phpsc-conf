<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;

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
        $supporter = $this->get('supporter.management.service')->findById($id);

        $this->response->setContentType('image/png');
        $this->response->setLastModified($supporter->getCompany()->getCreationTime());

        return $this->get('image.rendering.service')->resize(
            $supporter->getCompany()->getLogo(),
            md5('supporter' . $id),
            $this->request->query->get('w'),
            $this->request->query->get('h'),
            $this->request,
            $this->response
        );
    }
}
