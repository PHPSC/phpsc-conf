<?php
namespace Lcobucci\ActionMapper2\Errors;

class BadRequestException extends HttpException
{
	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return 400;
	}
}