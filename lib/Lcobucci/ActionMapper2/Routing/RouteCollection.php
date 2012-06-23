<?php
namespace Lcobucci\ActionMapper2\Routing;

use Lcobucci\ActionMapper2\Errors\PageNotFoundException;
use \InvalidArgumentException;
use \ReflectionClass;

class RouteCollection
{
    /**
     * @var array
     */
    private $routes;

    /**
     * Class contructor
     */
    public function __construct()
    {
        $this->routes = array();
    }

    /**
     * @param string $pattern
     * @param \Lcobucci\ActionMapper2\Routing\Route|\Closure|string $handler
     */
    public function append($pattern, $handler)
    {
        if (isset($this->routes[$pattern])) {
            throw new InvalidArgumentException(
                'Pattern already mapped'
            );
        }

        if (!$handler instanceof \Closure
            && !$this->isValidHandler($handler)) {
            throw new InvalidArgumentException(
                'You must pass a closure or a class that implements'
                . ' \Lcobucci\ActionMapper2\Routing\Route interface'
            );
        }

        $this->routes[$pattern] = RouteDefinitionCreator::create(
            $pattern,
            $handler
        );

        $this->sortByKeyLength();
    }

    /**
     * Sorts the collection by the patterns legth
     */
    protected function sortByKeyLength()
    {
        uksort(
            $this->routes,
            function ($one, $other)
            {
                $oneLength = strlen($one);
                $otherLength = strlen($other);

                return $oneLength > $otherLength
                       ? 1
                       : ($oneLength == $otherLength ? 0 : -1);
            }
        );

        $this->routes = array_reverse($this->routes, true);
    }

    /**
     * @param object|string $handler
     * @return boolean
     */
    protected function isValidHandler($handler)
    {
        if (is_string($handler) && strpos($handler, '::') !== false) {
            $handler = substr($handler, 0, strpos($handler, '::'));
        }

        if (is_object($handler)
            || (is_string($handler) && class_exists($handler))) {
            $reflection = new ReflectionClass($handler);

            return $reflection->implementsInterface(
                '\Lcobucci\ActionMapper2\Routing\Route'
            );
        }

        return false;
    }

    /**
     * @param string $path
     * @return \Otto\Component\Routing\CompiledRoute
     */
    public function findRouteFor($path)
    {
        foreach ($this->routes as $route) {
            if ($route->match($path)) {
                return $route;
            }
        }

        throw new PageNotFoundException('No route for the requested path');
    }
}