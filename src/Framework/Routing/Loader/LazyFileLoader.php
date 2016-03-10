<?php

namespace Framework\Routing\Loader;

use Framework\Routing\RouteCollection;

class LazyFileLoader implements LazyFileLoaderInterface
{
    private $config;
    private $loader;
    private $routes;
    private $loaded;

    public function __construct($config, FileLoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->routes = new RouteCollection();
        $this->loaded = false;
        $this->config = $config;
    }

    public function load()
    {
        if ($this->loaded) {
            return $this->routes;
        }

        $this->routes->merge($this->loader->load($this->config));
        $this->loaded = true;

        return $this->routes;
    }
}
