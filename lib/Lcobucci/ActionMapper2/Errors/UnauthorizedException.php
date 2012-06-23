<?php
namespace Lcobucci\ActionMapper2\Errors;

class UnauthorizedException extends HttpException
{
	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return 401;
	}
}