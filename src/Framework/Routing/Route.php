<?php

namespace Framework\Routing;

class Route
{
    private $path;
    private $parameters;
    private $methods;

    public function __construct($path, array $parameters = [], array $methods = [])
    {
        $this->path = $path;
        $this->parameters = $parameters;
        $this->methods = $methods;
    }

    public function getMethods()
    {
        $methods = $this->methods;

        if (in_array('GET', $methods) && !in_array('HEAD', $methods)) {
            $methods[] = 'HEAD';
        }

        return $methods;
    }

    public function match($path)
    {
        return $path === $this->path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}
