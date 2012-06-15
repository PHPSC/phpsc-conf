<?php
namespace Lcobucci\ActionMapper\Core;

use Lcobucci\ActionMapper\Http\Response;
use Lcobucci\ActionMapper\Http\Request;
use Lcobucci\ActionMapper\Http\Errors\InternalServerError;
use Lcobucci\ActionMapper\Http\Errors\HttpException;
use \ErrorException;
use \Exception;

abstract class AbstractErrorHandler
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->changePhpErrorHandler();
	}

	/**
	 * Changes the default PHP error handler (every error will be an exception)
	 */
	protected function changePhpErrorHandler()
	{
		set_error_handler(
			function($severity, $message, $fileName, $lineNumber)
			{
				throw new ErrorException($message, 0, $severity, $fileName, $lineNumber);
			}
		);
	}

	/**
	 * Handles the errors and show the error page
	 *
	 * @para Lcobucci\ActionMapper\Http\Request $request
	 * @param Exception $e
	 */
	public final function handleError(Request $request, Response $response, Exception $e)
	{
		if (!$e instanceof HttpException) {
			$e = new InternalServerError('Internal error occurred', null, $e);
		}

		$response->statusCode($e->getStatusCode());
		$this->renderErrorPage($request, $response, $e);
	}

	/**
	 * Renders the error page according with the exception
	 *
	 * @para Lcobucci\ActionMapper\Http\Request $request
	 * @param HttpException $e
	 */
	protected abstract function renderErrorPage(Request $request, Response $response, HttpException $e);
}
