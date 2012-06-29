<?php
namespace Lcobucci\ActionMapper2\Routing\Annotation;

use \Lcobucci\ActionMapper2\Routing\RouteDefinition;
use \Lcobucci\ActionMapper2\Routing\RouteDefinitionCreator;
use \Lcobucci\ActionMapper2\Errors\BadRequestException;
use \Lcobucci\ActionMapper2\Http\Request;
use \InvalidArgumentException;

/**
 * @Annotation
 * @Target({"METHOD"})
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Route
{
    /**
     * @var string
     */
    public $pattern = '/';

    /**
     * @var array
     */
    public $requirements = array();

    /**
     * @var array
     */
    public $methods = array('GET', 'POST', 'PUT', 'DELETE');

    /**
     * @var array
     */
    private $matchedArgs;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        if (isset($options['value'])) {
            $this->setPattern($options['value']);
        }

        if (isset($options['pattern'])) {
            $this->setPattern($options['pattern']);
        }

        if (isset($options['requirements'])) {
            $this->setRequirements($options['requirements']);
        }

        if (isset($options['methods'])) {
            $this->setMethods($options['methods']);
        }
    }

    /**
     * @param array $requirements
     * @throws InvalidArgumentException
     */
    protected function setRequirements($requirements)
    {
        if (!is_array($requirements)) {
            throw new InvalidArgumentException(
                'Route requirements must be an array'
            );
        }

        $this->requirements = $requirements;
    }

    /**
     * @param array $methods
     * @throws InvalidArgumentException
     */
    protected function setMethods($methods)
    {
        if (!is_array($methods)) {
            throw new InvalidArgumentException(
                'Route methods must be an array'
            );
        }

        foreach ($methods as $method) {
            if (!in_array($method, array('GET', 'POST', 'PUT', 'DELETE'))) {
                throw new InvalidArgumentException(
                    '"' . $method . '" is not a valid route method '
                    . '(only GET, POST, PUT, DELETE are allowed)'
                );
            }
        }

        $this->methods = $methods;
    }

    /**
     * @param string $pattern
     */
    protected function setPattern(&$pattern)
    {
        $this->pattern = RouteDefinitionCreator::preparePattern($pattern);
    }

    /**
     * @param \Lcobucci\ActionMapper2\Routing\RouteDefinition $route
     * @param \Lcobucci\ActionMapper2\Http\Request $request
     * @return boolean
     */
    public function match(RouteDefinition $route, Request $request)
    {
        if (!$this->validatePattern($route, $request)) {
            return false;
        }

        if (!$this->validateMethod($request)) {
            return false;
        }

        $this->validateRequirements();

        return true;
    }

    /**
     * @return array
     */
    public function getMatchedArgs()
    {
        return $this->matchedArgs;
    }

    /**
     * @param \Lcobucci\ActionMapper2\Routing\RouteDefinition $route
     * @param \Lcobucci\ActionMapper2\Http\Request $request
     * @return boolean
     */
    protected function validatePattern(RouteDefinition $route, Request $request)
    {
        $path = $this->getRequestedPath($route, $request);
        $regex = RouteDefinitionCreator::createRegex($this->pattern);

        if (preg_match($regex, $path, $this->matchedArgs)) {
            array_shift($this->matchedArgs);

            return true;
        }

        return false;
    }

    /**
     * @param \Lcobucci\ActionMapper2\Routing\RouteDefinition $route
     * @param \Lcobucci\ActionMapper2\Http\Request $request
     * @return string
     */
    protected function getRequestedPath(RouteDefinition $route, Request $request)
    {
        $pattern = rtrim($route->getPattern(), '/*');
        $regex = RouteDefinitionCreator::createRegex($pattern, false);
        $path = preg_replace($regex, '', $request->getRequestedPath());

        if (substr($path, 0, 1) != '/') {
            $path = '/' . $path;
        }

        if ($path != '/') {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    /**
     * @param \Lcobucci\ActionMapper2\Http\Request $request
     * @return boolean
     */
    protected function validateMethod(Request $request)
    {
        return in_array($request->getMethod(), $this->methods);
    }

    /**
     * Validates the requirements
     */
    protected function validateRequirements()
    {
        if (isset($this->requirements[0]) && is_array($this->matchedArgs)) {
            foreach ($this->matchedArgs as $index => $value) {
                if (isset($this->requirements[$index])) {
                    $this->validateRequirement(
                        $this->requirements[$index],
                        $value
                    );
                }
            }
        }
    }

    /**
     * @param string $expression
     * @param string $value
     * @throws \Lcobucci\ActionMapper2\Errors\BadRequestException
     */
    protected function validateRequirement($expression, $value)
    {
        if (strlen($expression) == 0) {
            return ;
        }

        if (!preg_match('/^' . $expression . '$/', $value)) {
            throw new BadRequestException(
                'The value "' . $value . '" is not compatible'
                . ' with the required expression "'
                . $expression . '"'
            );
        }
    }
}