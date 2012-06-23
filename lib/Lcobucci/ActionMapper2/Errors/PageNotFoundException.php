<?php
namespace Lcobucci\ActionMapper2\Errors;

class PageNotFoundException extends HttpException
{
	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return 404;
	}
}