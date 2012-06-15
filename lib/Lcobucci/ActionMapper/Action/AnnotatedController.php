<?php
namespace Lcobucci\ActionMapper\Action;

use \Lcobucci\ActionMapper\Http\Errors\PageNotFoundException;
use \Lcobucci\ActionMapper\Util\PathPatternComparer;
use \Lcobucci\ActionMapper\Annotations\RouteAnnotation;
use \Lcobucci\ActionMapper\Http\Response;
use \Lcobucci\ActionMapper\Http\Request;
use \Mindplay\Annotation\Core\Annotations;
use \Mindplay\Annotation\Core\AnnotationException;
use \ReflectionClass;
use \ReflectionMethod;

class AnnotatedController implements Action
{
	/**
	 * @var Lcobucci\ActionMapper\Http\Request
	 */
	private $request;

	/**
	 * @var Lcobucci\ActionMapper\Http\Response
	 */
	private $response;

	/**
	 * @var Lcobucci\ActionMapper\Annotations\RouteAnnotation
	 */
	private $route;

	/**
	 * @return \Lcobucci\ActionMapper\Http\Request
	 */
	protected function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return \Lcobucci\ActionMapper\Http\Response
	 */
	protected function getResponse()
	{
		return $this->response;
	}

    /**
     * @param \Lcobucci\ActionMapper\Http\Request $request
     * @param \Lcobucci\ActionMapper\Http\Response $request
     * @see Action::process()
     */
    public function process(Request $request, Response $response)
    {
    	$this->request = $request;
    	$this->response = $response;

		$method = $this->findMethod();

		$this->getResponse()->appendContent(
			$method->invokeArgs(
				$this,
				$this->getRequest()->getPathSegments()
			)
		);
    }

    /**
     * @return \ReflectionMethod
     */
    private function findMethod()
    {
    	$this->setAnnotationAlias();

        $reflection = new ReflectionClass($this);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $annotations = Annotations::ofMethod(
            	$method,
            	null,
            	'Lcobucci\ActionMapper\Annotations\RouteAnnotation'
            );

            if (isset($annotations[0]) && $this->validateRoute($annotations[0])) {
            	$this->route = $annotations[0];

				return $method;
            }
        }

        throw new PageNotFoundException('No action for this request');
    }

    /**
     * @param \Lcobucci\ActionMapper\Annotations\RouteAnnotation $route
     * @return boolean
     */
    private function validateRoute(RouteAnnotation $route)
    {
    	$path = implode('/', $this->getRequest()->getPathSegments());

    	if (PathPatternComparer::patternMatches($path, $route->pattern)) {
    		return in_array($this->getRequest()->getMethod(), $route->allowedMethods);
    	}

    	return false;
    }

    private function setAnnotationAlias()
    {
    	try {
    		Annotations::addAlias('Route', 'Lcobucci\ActionMapper\Annotations\RouteAnnotation');
    	} catch (AnnotationException $e) {
    		// Ignore
    	}
    }

    /**
     * @return \Lcobucci\ActionMapper\Core\Application
     */
    protected function getApplication()
    {
    	return $this->getRequest()->getApplication();
    }

    /**
     * @return object
     */
    protected function getDependencyContainer()
    {
    	return $this->getApplication()->getDependencyContainer();
    }
}