<?php
namespace Lcobucci\ActionMapper\Http\Errors;

class ForbiddenException extends HttpException
{
	/**
	 * @return string
	 */
	public function getStatusCode()
	{
		return '403 Forbidden';
	}
}