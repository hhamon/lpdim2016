<?php

namespace Framework\Routing;

use Framework\Routing\Loader\FileLoaderInterface;

class Router implements RouterInterface
{
    private $routes;
    private $loader;
    private $configuration;

    public function __construct($configuration, FileLoaderInterface $loader)
    {
        $this->configuration = $configuration;
        $this->loader = $loader;
    }

    public function match(RequestContext $context)
    {
        if (null === $this->routes) {
            $this->routes = $this->loader->load($this->configuration);
        }

        $path = $context->getPath();
        if (!$route = $this->routes->match($path)) {
            throw new RouteNotFoundException(sprintf('No route found for path %s.', $path));
        }

        $method = $context->getMethod();
        $allowedMethods = $route->getMethods();
        if (count($allowedMethods) && !in_array($method, $allowedMethods)) {
            throw new MethodNotAllowedException($method, $allowedMethods);
        }

        return $route->getParameters();
    }
}
