<?php
namespace Lcobucci\ActionMapper\Core;

use Lcobucci\ActionMapper\Http\Response;
use Lcobucci\ActionMapper\Http\Errors\HttpException;
use Lcobucci\ActionMapper\Http\Request;

class DefaultErrorHandler extends AbstractErrorHandler
{
	/**
     * @see Lcobucci\ActionMapper\Core\AbstractErrorHandler::renderErrorPage()
     */
    protected function renderErrorPage(Request $request, Response $response, HttpException $e)
    {
    	$response->setContent('<pre>'. $e . '</pre>');
    }
}