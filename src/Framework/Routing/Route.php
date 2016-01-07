<?php

namespace Framework\Routing;

class Route
{
    private $path;
    private $parameters;

    public function __construct($path, array $parameters = [])
    {
        $this->path = $path;
        $this->parameters = $parameters;
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
