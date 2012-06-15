<?php
namespace Lcobucci\ActionMapper\Http\Errors;

class MethodNotAllowedException extends HttpException
{
	/**
	 * @return string
	 */
	public function getStatusCode()
	{
		return '405 Method Not Allowed';
	}
}