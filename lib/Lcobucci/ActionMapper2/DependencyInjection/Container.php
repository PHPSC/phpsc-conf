<?php
namespace Lcobucci\ActionMapper2\DependencyInjection;

use \Lcobucci\ActionMapper2\Application;

class Container extends \Symfony\Component\DependencyInjection\Container
{
    /**
     * @var \Lcobucci\ActionMapper2\Application
     */
    protected $application;

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Gets the 'session' service.
     *
     * @return \Symfony\Component\HttpFoundation\Session
     */
    protected function getSessionService()
    {
        return $this->services['session'] = $this->application->getSession();
    }
}