<?php
namespace Lcobucci\ActionMapper2\Routing;

use \Lcobucci\ActionMapper2\Http\Response;
use \Lcobucci\ActionMapper2\Http\Request;
use \Lcobucci\ActionMapper2\Application;
use \BadMethodCallException;

class Controller implements Route
{
    /**
     * @var \Lcobucci\ActionMapper2\Http\Request
     */
    protected $request;

    /**
     * @var \Lcobucci\ActionMapper2\Http\Response
     */
    protected $response;

    /**
     * @var \Lcobucci\ActionMapper2\Application
     */
    protected $application;

    /**
     * @see \Lcobucci\ActionMapper2\Routing\Route::setRequest()
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @see \Lcobucci\ActionMapper2\Routing\Route::setResponse()
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @see \Lcobucci\ActionMapper2\Routing\Route::setApplication()
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param string $serviceId
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function get($serviceId)
    {
        if ($this->application->getDependencyContainer() === null) {
            throw new BadMethodCallException(
                'The dependency container must be defined'
            );
        }

        return $this->application->getDependencyContainer()->get($serviceId);
    }

    /**
     * @param string $path
     * @param boolean $interrupt
     */
    public function forward($path, $interrupt = false)
    {
        $this->application->forward($path, $interrupt);
    }

    /**
     * @param string $url
     */
    public function redirect($url)
    {
        $this->application->redirect($url);
    }
}