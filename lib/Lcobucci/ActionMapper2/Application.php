<?php
namespace Lcobucci\ActionMapper2;

use \Lcobucci\ActionMapper2\DependencyInjection\Container as ActionMapperContainer;
use \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use \Symfony\Component\HttpFoundation\Session\SessionInterface;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Lcobucci\ActionMapper2\Routing\RouteManager;
use \Lcobucci\ActionMapper2\Errors\ErrorHandler;
use \Lcobucci\ActionMapper2\Http\Response;
use \Lcobucci\ActionMapper2\Http\Request;

class Application
{
    /**
     * @var \Lcobucci\ActionMapper2\Routing\RouteManager
     */
    protected $routeManager;

    /**
     * @var \Lcobucci\ActionMapper2\Errors\ErrorHandler
     */
    protected $errorHandler;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var \Lcobucci\ActionMapper2\Http\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * @param \Lcobucci\ActionMapper2\Routing\RouteManager $routeManager
     * @param \Lcobucci\ActionMapper2\Errors\ErrorHandler $errorHandler
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $dependencyContainer
     */
    public function __construct(
        RouteManager $routeManager,
        ErrorHandler $errorHandler,
        ContainerInterface $dependencyContainer = null
    ) {
        $this->routeManager = $routeManager;
        $this->errorHandler = $errorHandler;

        if ($dependencyContainer !== null) {
            $this->setDependencyContainer($dependencyContainer);
        }
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $dependencyContainer
     */
    public function setDependencyContainer(ContainerInterface $dependencyContainer)
    {
        if ($dependencyContainer instanceof ActionMapperContainer) {
            $dependencyContainer->setApplication($this);
        }

        $this->dependencyContainer = $dependencyContainer;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * @return \Lcobucci\ActionMapper2\Routing\RouteManager
     */
    public function getRouteManager()
    {
        return $this->routeManager;
    }

    /**
     * @return \Lcobucci\ActionMapper2\Http\Request
     */
    public function getRequest()
    {
        if ($this->request === null) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }

    /**
     * @return \Lcobucci\ActionMapper2\Http\Response
     */
    public function getResponse()
    {
        if ($this->response === null) {
            $this->response = new Response();
        }

        return $this->response;
    }

    /**
     * @param string $name
     */
    public function startSession($name = null)
    {
        $this->setDefaultSession($name);
        $this->getSession()->start();
    }

    /**
     * @param string $name
     */
    protected function setDefaultSession($name = null)
    {
        if ($this->getRequest()->hasSession()) {
            return ;
        }

        $options = array();

        if ($name !== null) {
            $options['name'] = $name;
        }

        $this->setSession(new Session(new NativeSessionStorage($options)));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        if (!$this->getRequest()->hasSession()) {
            $this->getRequest()->setSession($session);
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getSession()
    {
        return $this->getRequest()->getSession();
    }

    /**
     * @param string $url
     */
    public function redirect($url)
    {
        if (strpos($url, 'http') !== 0) {
            $url = $this->getRequest()->getBasePath() . $url;
        }

        $this->getResponse()->redirect($url);
        $this->sendResponse();
    }

    /**
     * @param string $path
     * @param boolean $interrupt
     */
    public function forward($path, $interrupt = false)
    {
        try {
            $request = $this->getRequest();
            $previousPath = $request->getRequestedPath();

            $request->setRequestedPath($path);
            $this->routeManager->process($this);
            $request->setRequestedPath($previousPath);
        } catch (\Exception $error) {
            $this->errorHandler->handle(
                $this->getRequest(), $this->getResponse(), $error
            );
        }

        if (isset($error) || $interrupt) {
            $this->sendResponse();
        }
    }

    /**
     * Executes the application
     */
    public function run()
    {
        try {
            ob_start();
            $this->routeManager->process($this);
            ob_end_clean();
        } catch (\Exception $error) {
            $this->errorHandler->handle(
                $this->getRequest(), $this->getResponse(), $error
            );
        }

        $this->sendResponse();
    }

    /**
     * Sends the response to the browser
     */
    protected function sendResponse()
    {
        $response = $this->getResponse();

        $response->prepare($this->getRequest());
        $response->send();
    }
}