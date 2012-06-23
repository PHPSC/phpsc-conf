<?php
namespace Lcobucci\ActionMapper2\Errors;

class ForbiddenException extends HttpException
{
	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return 403;
	}
}