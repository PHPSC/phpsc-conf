<?php
namespace Lcobucci\ActionMapper2\Errors;

class MethodNotAllowedException extends HttpException
{
	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return 405;
	}
}