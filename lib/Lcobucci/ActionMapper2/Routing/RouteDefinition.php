<?php
namespace Lcobucci\ActionMapper2\Routing;

use \Lcobucci\ActionMapper2\Errors\PageNotFoundException;
use \Doctrine\Common\Annotations\AnnotationReader;
use \Lcobucci\ActionMapper2\Application;
use \ReflectionClass;
use \ReflectionMethod;
use \RuntimeException;

class RouteDefinition
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var string
     */
    protected $regex;

    /**
     * @var \Lcobucci\ActionMapper2\Routing\Route|\Lcobucci\ActionMapper2\Routing\Filter|\Closure|string
     */
    protected $handler;

    /**
     * @var array
     */
    protected $matchedArgs;

    /**
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    protected $annotationParser;

    /**
     * @param string $pattern
     * @param string $regex
     * @param \Lcobucci\ActionMapper2\Routing\Route|\Lcobucci\ActionMapper2\Routing\Filter|\Closure|string $handler
     */
    public function __construct($pattern, $regex, $handler)
    {
        $this->pattern = $pattern;
        $this->regex = $regex;
        $this->handler = $handler;
    }

    /**
     * @param \Doctrine\Common\Annotations\AnnotationReader $annotationParser
     */
    public function setAnnotationParser(AnnotationReader $annotationParser)
    {
        $this->annotationParser = $annotationParser;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $path
     * @return boolean
     */
    public function match($path)
    {
        if (preg_match($this->regex, $path, $this->matchedArgs)) {
            array_shift($this->matchedArgs);

            return true;
        }

        return false;
    }

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    public function process(Application $application)
    {
        $content = $this->getContent($application);

        if ($content) {
            $application->getResponse()->appendContent((string) $content);
        }
    }

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     * @return string
     */
    protected function getContent(Application $application)
    {
        if ($this->handler instanceof \Closure) {
            return call_user_func_array($this->handler, $this->matchedArgs);
        }

        $method = null;

        $handler = $this->getHandler($method);
        $handler->setRequest($application->getRequest());
        $handler->setResponse($application->getResponse());
        $handler->setApplication($application);

        if ($handler instanceof Filter) {
            return call_user_func_array(
                array($handler, 'process'),
                $this->matchedArgs
            );
        }

        if ($method !== null) {
            $this->validateCustomAnnotations(
                $application,
                new ReflectionMethod($handler, $method)
            );

            return call_user_func_array(
                array($handler, $method),
                $this->matchedArgs
            );
        }

        return $this->parseAnnotation($handler, $application);
    }

    /**
     * @param string $method
     * @return \Lcobucci\ActionMapper2\Routing\Route|\Lcobucci\ActionMapper2\Routing\Filter
     */
    protected function getHandler(&$method)
    {
        if (is_string($this->handler)) {
            if (strpos($this->handler, '::') !== false) {
                list($class, $method) = explode('::', $this->handler);
            } else {
                $class = $this->handler;
            }

            return new $class();
        }

        return $this->handler;
    }

    /**
     * @param \Lcobucci\ActionMapper2\Routing\Route $handler
     * @param \Lcobucci\ActionMapper2\Application $application
     * @throws \RuntimeException
     * @throws \PageNotFoundException
     */
    protected function parseAnnotation(Route $handler, Application $application)
    {
        if ($this->annotationParser === null) {
            throw new RuntimeException('Annotation parser is not setted');
        }

        $class = new ReflectionClass($handler);

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $annotation = $this->annotationParser->getMethodAnnotation(
                $method,
                '\Lcobucci\ActionMapper2\Routing\Annotation\Route'
            );

            if ($annotation
                && $annotation->match($this, $application->getRequest())) {
                $this->validateCustomAnnotations($application, $method);

                return $method->invokeArgs(
                    $handler,
                    (array) $annotation->getMatchedArgs()
                );
            }
        }

        throw new PageNotFoundException('No route for the requested path');
    }

    /**
     * Validate custom annotations
     *
     * @param \Lcobucci\ActionMapper2\Application $application
     * @param \ReflectionMethod $method
     */
    protected function validateCustomAnnotations(Application $application, ReflectionMethod $method)
    {
        // Override if needed
    }
}