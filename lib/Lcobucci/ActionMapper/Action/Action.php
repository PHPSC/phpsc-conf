<?php
namespace Lcobucci\ActionMapper\Action;

use Lcobucci\ActionMapper\Http\Response;
use Lcobucci\ActionMapper\Http\Request;

interface Action
{
	/**
	 * @param Lcobucci\ActionMapper\Http\Request $request
	 * @param Lcobucci\ActionMapper\Http\Response $request
	 */
	public function process(Request $request, Response $response);
}