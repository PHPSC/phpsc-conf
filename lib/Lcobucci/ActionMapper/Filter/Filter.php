<?php
namespace Lcobucci\ActionMapper\Filter;

use Lcobucci\ActionMapper\Http\Response;

use Lcobucci\ActionMapper\Http\Request;

interface Filter
{
	/**
	 * @param \Lcobucci\ActionMapper\Http\Request $request
	 */
	public function applyFilter(Request $request, Response $response);
}