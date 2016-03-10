<?php

namespace Framework\Routing\Loader;

use Framework\Routing\RouteCollection;

interface LazyFileLoaderInterface
{
    /**
     * Returns the route collection of loaded route;
     *
     * @return RouteCollection
     */
    public function load();
}
