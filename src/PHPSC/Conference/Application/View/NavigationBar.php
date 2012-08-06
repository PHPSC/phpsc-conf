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
        return array(
            array('Página Inicial', '/'),
            array('Sobre o Evento', '/about'),
            array('O Local', '/venue'),
            array('Inscrições', '/registration'),
            array('Chamada de Trabalhos', '/call4papers'),
            array('Contato', '/contact')
        );
    }

    /**
     * @param string $path
     * @return boolean
     */
    public function isActive($path)
    {
        return $this->application->getRequest()->getRequestedPath() == $path;
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
}