<?php
namespace PHPSC\Conference\Application\Action;

use \Lcobucci\ActionMapper2\Routing\Annotation\Route;
use \Lcobucci\ActionMapper2\Routing\Controller;
use \PHPSC\Conference\Application\View\Pages\Contact as ContactForm;
use \PHPSC\Conference\Application\View\Main;

class Contact extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function displayForm()
    {
        return Main::create(new ContactForm(), $this->application);
    }
}