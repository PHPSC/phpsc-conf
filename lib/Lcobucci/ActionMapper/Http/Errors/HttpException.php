<?php
namespace Lcobucci\ActionMapper\Http\Errors;

abstract class HttpException extends \Exception
{
	/**
	 * @return string
	 */
	public abstract function getStatusCode();
}