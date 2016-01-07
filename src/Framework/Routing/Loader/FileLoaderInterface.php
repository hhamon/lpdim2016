<?php

namespace Framework\Routing\Loader;

use Framework\Routing\RouteCollection;

interface FileLoaderInterface
{
    /**
     * Loads a routing configuration file.
     *
     * @param string $path The configuration file path
     * @return RouteCollection
     */
    public function load($path);
}
