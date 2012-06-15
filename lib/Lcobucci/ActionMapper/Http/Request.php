<?php
namespace Lcobucci\ActionMapper\Http;

use Lcobucci\ActionMapper\Core\Application;
use \InvalidArgumentException;

class Request
{
	/**
	 * @var \Lcobucci\ActionMapper\Core\Application
	 */
	private $application;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @param \Lcobucci\ActionMapper\Core\Application $application
	 */
	public function __construct(Application $application)
	{
		$this->application = $application;
	}

	/**
	 * Returns the requested method
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Returns the request parameters
	 *
	 * @return array
	 */
	public function getParams()
	{
		if (is_null($this->params)) {
			$vars = array_merge($_GET, $_POST);

			if ($this->getMethod() == 'PUT' || $this->getMethod() == 'DELETE') {
				$vars = array_merge($vars, $this->getStreamParams());
			}

			$this->params = $vars;
		}

		return $this->params;
	}
	
	/**
	 * Return a request specific parameter. If the parameter is NULL,
	 * the $defaultValue will be returned.
	 * 
	 * @param string $key The parameter to return
	 * @param mixed $defaultValue The default value to be returned in case of NULL param
	 * @return string
	 */
	public function getParam($param, $defaultValue = NULL)
	{
		$params = $this->getParams();

		return isset($params[$param]) ? $params[$param] : $defaultValue;
	}

	/**
	 * Returns the PUT and DELETE request parameters
	 *
	 * @return array
	 */
	protected function getStreamParams()
	{
		$params = array();
		parse_str(file_get_contents('php://input'), $params);

		return $params;
	}

	/**
	 * Returns the requested URI
	 *
	 * @return string
	 */
	public function getPath()
	{
		if (is_null($this->path)) {
			$uri = parse_url($_SERVER['REQUEST_URI']);
			$uri = $uri['path'];

			if ($this->getApplication()->getPath() != '/') {
			    $uri = str_replace($this->getApplication()->getPath(), '', $uri);
			} elseif (substr($uri, 0, 1) == '/') {
			    $uri = substr($uri, 1);
			}

			$this->setPath($uri);
		}

		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->getApplication()->getUrl() . $this->getPath();
	}

	/**
	 * Returns the path segments (removing the first one)
	 *
	 * @return array
	 */
	public function getPathSegments()
	{
		$segments = explode('/', $this->getPath());
		array_shift($segments);

		if (count($segments) == 0) {
			$segments[] = '';
		}

		return $segments;
	}

	/**
	 * Returns the path's segment by the position
	 *
	 * @param int $segment
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function getSegmentByPosition($position)
	{
		if (preg_match('/^[0-9]{1,}$/', $position) == 0) {
			throw new InvalidArgumentException('The position must be an integer');
		}

		$pathSegments = explode('/', $this->getPath());

		if (isset($pathSegments[$position])) {
			return $pathSegments[$position];
		}

		return '';
	}

	/**
	 * @return \Lcobucci\ActionMapper\Core\Application
	 */
	public function getApplication()
	{
		return $this->application;
	}

	/**
	 * @return \Lcobucci\Session\SessionStorage
	 */
	public function getSession()
	{
		return $this->getApplication()->getSession();
	}

	/**
	 * Checks if the request was made by ajax by looking for the
	 * "X_Requested_With" header sent by most javascript frameworks,
	 * like jQuery, Prototype and ExtJS.
	 *
	 * @return boolean
	 */
	public function isAjax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
}
