<?php
namespace Lcobucci\ActionMapper\Action;

use Lcobucci\ActionMapper\Http\Response;
use Lcobucci\ActionMapper\Http\Request;
use Lcobucci\ActionMapper\Util\PathPatternComparer;
use \ReflectionClass;

class Mapper
{
	/**
	 * @var array
	 */
	private $actions;

	/**
	 * @var string
	 */
	private $defaultNamespace;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->actions = array();
		$this->defaultNamespace = '';
	}

	/**
	 * Returns the mapped actions
	 *
	 * @return array
	 */
	protected function getActions()
	{
		return $this->actions;
	}

	/**
	 * @return string
	 */
	protected function getDefaultNamespace()
	{
		return $this->defaultNamespace;
	}

	/**
	 * @param string $defaultNamespace
	 */
	public function setDefaultNamespace($defaultNamespace)
	{
		$this->defaultNamespace = $defaultNamespace;
	}

	/**
	 * @param string $pattern
	 * @param Action $action
	 * @throws ActionAlreadyMappedException
	 */
	public function attachAction($pattern, Action $action)
	{
		if (isset($this->actions[$pattern])) {
			throw new ActionAlreadyMappedException('Already exists an action to the pattern "' . $pattern . '"');
		}

		$this->actions[$pattern] = $action;
	}

	/**
	 * @param Lcobucci\ActionMapper\Http\Request $request
	 * @param Lcobucci\ActionMapper\Http\Response $request
	 * @throws ActionNotFoundException
	 */
	public function process(Request $request, Response $response)
	{
		if ($action = $this->getByRequest($request)) {
			$action->process($request, $response);
		} else {
			throw new ActionNotFoundException('Action not found for this URI');
		}
	}

	/**
	 * @param Lcobucci\ActionMapper\Http\Request $request
	 * @return Lcobucci\ActionMapper\Action\Action
	 */
	protected function getByRequest(Request $request)
	{
		$action = null;
		$matchedAction = $this->getActionByPath($request->getPath());

		if (is_null($matchedAction) || $matchedAction->getPattern() == '*') {
			$action = $this->getByAutoDiscovery($request);
		}

		if (is_null($action) && !is_null($matchedAction)) {
			$action = $matchedAction->getAction();
		}

		return $action;
	}

	/**
	 * @param string $path
	 * @return Lcobucci\ActionMapper\Action\MatchedAction
	 */
	protected function getActionByPath($path)
	{
		$action = null;
		$longestPattern = null;

		foreach ($this->getActions() as $actionPattern => $actionInstance) {
			if (PathPatternComparer::patternMatches($path, $actionPattern)
				&& strlen($actionPattern) > strlen($longestPattern))
			{
				$longestPattern = $actionPattern;
				$action = $actionInstance;
			}
		}

		if ($action) {
			return new MatchedAction($action, $longestPattern);
		}
	}

	/**
	 * @param Lcobucci\ActionMapper\Http\Request $request
	 * @return Lcobucci\ActionMapper\Action\Action
	 */
	protected function getByAutoDiscovery(Request $request)
	{
		$action = null;
		$actionPath = $request->getSegmentByPosition(0);
		$className = ucfirst($actionPath);

		$className = $this->getDefaultNamespace() . '\\' . $className . 'ActionController';

		if (class_exists($className)) {
			$reflection = new ReflectionClass($className);

			if ($reflection->implementsInterface('Lcobucci\ActionMapper\Action\Action')) {
				$action = new $className();
				$this->attachAction($actionPath, $action);
				$this->attachAction($actionPath . '/*', $action);
			}
		}

		return $action;
	}
}