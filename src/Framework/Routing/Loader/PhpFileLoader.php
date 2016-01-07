<?php

namespace Framework\Routing\Loader;

use Framework\Routing\RouteCollection;

class PhpFileLoader implements FileLoaderInterface
{
    public function load($path)
    {
        if ('php' !== pathinfo($path, PATHINFO_EXTENSION)) {
            throw new UnsupportedFileTypeException(sprintf(
                'File %s must be a valid PHP file.',
                $path
            ));
        }

        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf(
                'File %s is not readable or does not exist.',
                $path
            ));
        }

        $routes = include($path);
        if (!$routes instanceof RouteCollection) {
            throw new \RuntimeException(sprintf(
                'File %s must return a RouteCollection instance.',
                $path
            ));
        }

        return $routes;
    }
}
