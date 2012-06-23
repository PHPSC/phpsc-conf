<?php
namespace Lcobucci\ActionMapper2\Routing;

use \Lcobucci\ActionMapper2\Http\Response;
use \Lcobucci\ActionMapper2\Http\Request;
use \Lcobucci\ActionMapper2\Application;

abstract class Filter
{
    /**
     * @var \Lcobucci\ActionMapper2\Application
     */
    protected $application;

    /**
     * @var \Lcobucci\ActionMapper2\Http\Request
     */
    protected $request;

    /**
     * @var \Lcobucci\ActionMapper2\Http\Response
     */
    protected $response;

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param \Lcobucci\ActionMapper2\Http\Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Lcobucci\ActionMapper2\Http\Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Process the filter's job
     */
    public abstract function process();
}