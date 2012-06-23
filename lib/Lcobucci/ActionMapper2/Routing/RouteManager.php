<?php
namespace Lcobucci\ActionMapper2\Routing;

use \Lcobucci\ActionMapper2\Errors\PageNotFoundException;
use \Doctrine\Common\Annotations\AnnotationReader;
use \Lcobucci\ActionMapper2\Application;
use \RuntimeException;

class RouteManager
{
    /**
     * @var \Lcobucci\ActionMapper2\Routing\RouteCollection
     */
    private $routes;

    /**
     * @var \Lcobucci\ActionMapper2\Routing\FilterCollection
     */
    private $filters;

    /**
     * @param \Lcobucci\ActionMapper2\Routing\RouteCollection $routes
     * @param \Lcobucci\ActionMapper2\Routing\FilterCollection $filters
     */
    public function __construct(
        RouteCollection $routes = null,
        FilterCollection $filters = null
    ) {
        $this->routes = $routes ?: new RouteCollection();
        $this->filters = $filters ?: new FilterCollection();
    }

    /**
     * @param string $pattern
     * @param string|object $handler
     */
    public function addRoute($pattern, $handler)
    {
        $this->routes->append($pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string|object $hander
     * @param boolean $before
     */
    public function addFilter($pattern, $handler, $before = true)
    {
        $this->filters->append($pattern, $before, $handler);
    }

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    public function process(Application $application)
    {
        $this->processFilters($application);
        $this->processRoute($application);
        $this->processFilters($application, false);
    }

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     * @param boolean $before
     */
    protected function processFilters(Application $application, $before = true)
    {
        $filters = $this->filters->findFiltersFor(
            $application->getRequest()->getRequestedPath(),
            $before
        );

        foreach ($filters as $filter) {
            $filter->process($application);
        }
    }

    /**
     * @param \Lcobucci\ActionMapper2\Application $application
     */
    protected function processRoute(Application $application)
    {
        $route = $this->routes->findRouteFor(
            $application->getRequest()->getRequestedPath()
        );

        $route->process($application);
    }
}