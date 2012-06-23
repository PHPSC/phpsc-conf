<?php
namespace Lcobucci\ActionMapper2\Errors;

use \Lcobucci\ActionMapper2\Http\Request;
use \Lcobucci\ActionMapper2\Http\Response;
use \ErrorException;
use \Exception;

abstract class ErrorHandler
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
				throw new ErrorException(
			        $message,
			        0,
			        $severity,
			        $fileName,
			        $lineNumber
		        );
			}
		);
	}

	/**
	 * @param \Lcobucci\ActionMapper2\Http\Request $request
	 * @param \Lcobucci\ActionMapper2\Http\Response $response
	 * @param \Exception $e
	 */
	public final function handle(
        Request $request,
        Response $response,
        Exception $e
    ) {
		if (!$e instanceof HttpException) {
			$e = new InternalServerError('Internal error occurred', null, $e);
		}

		$response->setStatusCode($e->getStatusCode());
		$response->setContent($this->getErrorContent($request, $response, $e));
	}

	/**
	 * Renders the error page according with the exception
	 *
	 * @param \Lcobucci\ActionMapper2\Http\Request $request
	 * @param \Lcobucci\ActionMapper2\Http\Response $response
	 * @param \Lcobucci\ActionMapper2\Errors\HttpException $e
	 */
	protected abstract function getErrorContent(
        Request $request,
        Response $response,
        HttpException $e
    );
}