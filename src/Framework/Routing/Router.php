<?php

namespace Framework\Routing;

use Framework\Routing\Loader\LazyFileLoaderInterface;

class Router implements UrlMatcherInterface
{
    private $loader;

    public function __construct(LazyFileLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function match(RequestContext $context)
    {
        $routes = $this->loader->load();

        $path = $context->getPath();
        if (!$route = $routes->match($path)) {
            throw new RouteNotFoundException(sprintf('No route found for path %s.', $path));
        }

        $method = $context->getMethod();
        $allowedMethods = $route->getMethods();
        if (count($allowedMethods) && !in_array($method, $allowedMethods)) {
            throw new MethodNotAllowedException($method, $allowedMethods);
        }

        return array_merge(
            [ '_route' => $routes->getName($route) ],
            $route->getParameters()
        );
    }
}
