<?php
namespace PHPSC\Conference\Application\View;

use \Lcobucci\DisplayObjects\Core\UIComponent;
use \Lcobucci\ActionMapper2\Application;

class NavigationBar extends UIComponent
{
    /**
     * @var \Lcobucci\ActionMapper2\Application
     */
    protected $application;

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $event = $this->getEventManagement()->findCurrentEvent();

        $items = array(
            array('Página Inicial', ''),
            array('Sobre o Evento', 'about'),
            array('O Local', 'venue'),
            array('Inscrições', 'registration')
        );

        if ($event->isSubmissionsInterval(new \DateTime())) {
            $items[] = array('Chamada de Trabalhos', 'call4papers');
        } else {
            $items[] = array('Grade de Palestras', 'talks');
        }

        $items[] = array('Contato', 'contact');



        return $items;
    }

    /**
     * @return \stdClass
     */
    public function getTwitterUser()
    {
        $provider = $this->getAuthenticationService();

        return $provider->getTwitterUser();
    }

    /**
     * @return \PHPSC\Conference\Application\Service\AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->application->getDependencyContainer()->get('authentication.service');
    }

    /**
     * @return \PHPSC\Conference\Domain\Service\EventManagementService
     */
    protected function getEventManagement()
    {
        return $this->application->getDependencyContainer()->get('event.management.service');
    }
}
