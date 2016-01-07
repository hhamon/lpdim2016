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

    public function match($path)
    {
        foreach ($this->routes as $route) {
            if ($route->match($path)) {
                return $route;
            }
        }
    }

    public function add($name, Route $route)
    {
        if (isset($this->routes[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'A route already exists for the name "%s".',
                $name
            ));
        }

        $this->routes[$name] = $route;
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
