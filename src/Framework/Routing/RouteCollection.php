<?php

namespace Framework\Routing;

class RouteCollection implements \Iterator, \Countable
{
    /**
     * An array of Route instances.
     *
     * @var Route[]
     */
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function getName(Route $route)
    {
        foreach ($this->routes as $name => $oneRoute) {
            if ($route === $oneRoute) {
                return $name;
            }
        }

        throw new \RuntimeException('Route is not registered in the collection.');
    }

    public function merge(RouteCollection $routes, $override = false)
    {
        if ($routes === $this) {
            throw new \LogicException('A routes collection cannot merge into itself.');
        }

        foreach ($routes as $name => $route) {
            $this->add($name, $route, $override);
        }
    }

    public function match($path)
    {
        foreach ($this->routes as $route) {
            if ($route->match($path)) {
                return $route;
            }
        }
    }

    public function add($name, Route $route, $override = false)
    {
        if (isset($this->routes[$name]) && !$override) {
            throw new \InvalidArgumentException(sprintf(
                'A route already exists for the name "%s".',
                $name
            ));
        }

        $this->routes[$name] = $route;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getRoute($name)
    {
        if (!isset($this->routes[$name])) {
            throw new RouteNotFoundException(sprintf('Route "%s" does not exist.', $name));
        }

        return $this->routes[$name];
    }

    public function current()
    {
        return current($this->routes);
    }

    public function next()
    {
        next($this->routes);
    }

    public function key()
    {
        return key($this->routes);
    }

    public function valid()
    {
        return $this->current() instanceof Route;
    }

    public function rewind()
    {
        reset($this->routes);
    }

    public function count()
    {
        return count($this->routes);
    }
}
