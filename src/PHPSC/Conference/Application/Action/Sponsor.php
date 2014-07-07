<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;
use PHPSC\Conference\Domain\Entity\Sponsor as SponsorEntity;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Sponsor extends Controller
{
    /**
     * @Route("/", methods={"GET"}, contentType={"image/*"})
     * @param int $id
     */
    public function displayLogo($id)
    {
        /* @var $sponsor SponsorEntity */
        $sponsor = $this->get('sponsor.repository')->find($id);

        return $this->get('image.rendering.service')->resize(
            $sponsor->getCompany()->getLogo(),
            $this->request->query->get('w'),
            $this->request->query->get('h'),
            $this->request,
            $this->response
        );
    }
}
