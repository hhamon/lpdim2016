<?php

namespace Framework\Routing;

class Router implements RouterInterface
{
    private $routes;
    private $configuration;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    public function match($path)
    {
        if (null === $this->routes) {
            $this->routes = include($this->configuration);
        }

        if (!$route = $this->routes->match($path)) {
            throw new RouteNotFoundException(sprintf('No route found for path %s.', $path));
        }

        return $route->getParameters();
    }
}
