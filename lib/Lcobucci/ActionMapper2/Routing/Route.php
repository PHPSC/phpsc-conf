<?php
namespace Lcobucci\ActionMapper2\Routing;

use \Lcobucci\ActionMapper2\Http\Response;
use \Lcobucci\ActionMapper2\Http\Request;
use \Lcobucci\ActionMapper2\Application;

interface Route
{
    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    public function setApplication(Application $application);

    /**
     * @param \Lcobucci\ActionMapper2\Http\Request $request
     */
    public function setRequest(Request $request);

    /**
     * @param \Lcobucci\ActionMapper2\Http\Response $response
     */
    public function setResponse(Response $response);
}