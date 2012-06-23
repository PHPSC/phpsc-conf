<?php
namespace Lcobucci\ActionMapper2\Errors;

class InternalServerError extends HttpException
{
	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return 500;
	}
}