<?php
namespace Lcobucci\ActionMapper\Filter;

use Lcobucci\ActionMapper\Util\PathPatternComparer;
use Lcobucci\ActionMapper\Http\Response;
use Lcobucci\ActionMapper\Http\Request;

class FilterChain
{
	/**
	 * @var array
	 */
	private $filters;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->filters = array();
	}

	/**
	 * Returns the configured filters
	 *
	 * @return array
	 */
	protected function getFilters()
	{
		return $this->filters;
	}

	/**
	 * Attaches a new filter on the chain
	 *
	 * @param string $pattern
	 * @param Lcobucci\ActionMapper\Filter\Filter $filter
	 */
	public function attachFilter($pattern, Filter $filter)
	{
		$id = count($this->filters);

		$this->filters[$id . ';' . $pattern] = $filter;
	}

	/**
	 * @param Lcobucci\ActionMapper\Http\Request $request
	 * @param Lcobucci\ActionMapper\Http\Response $request
	 */
	public function applyFilters(Request $request, Response $response)
	{
		foreach ($this->getFiltersByPath($request->getPath()) as $filter) {
			$filter->applyFilter($request, $response);
		}
	}

	/**
	 * @param string $uri
	 * @return Lcobucci\ActionMapper\Filter\Filter[]
	 */
	protected function getFiltersByPath($path)
	{
		$filters = array();

		foreach ($this->getFilters() as $filterPattern => $filter) {
			$filterPattern = explode(';', $filterPattern);
			$filterPattern = $filterPattern[1];

			if (PathPatternComparer::patternMatches($path, $filterPattern)) {
				$filters[] = $filter;
			}
		}

		return $filters;
	}
}