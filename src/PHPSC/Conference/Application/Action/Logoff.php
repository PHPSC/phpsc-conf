<?php
namespace PHPSC\Conference\Application\Action;

use Lcobucci\ActionMapper2\Routing\Annotation\Route;
use Lcobucci\ActionMapper2\Routing\Controller;

class Logoff extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function logoff()
    {
        $this->request->getSession()->invalidate();
        $this->redirect('/');
    }
}
