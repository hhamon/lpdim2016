<?php

namespace Framework\Routing\Loader;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

abstract class AbstractFileLoader implements FileLoaderInterface
{
    const VALID_ROUTE_NAME_PATTERN = '/^(?:[a-zA-Z0-9_]+)/';

    protected static $allowedRouteKeys = ['path', 'params', 'requirements', 'methods'];

    abstract protected function assertSupportedFileType($path);

    abstract protected function doParseContents($path, $contents);

    public function load($path)
    {
        $this->assertSupportedFileType($path);

        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf(
                'File %s is not readable or does not exist.',
                $path
            ));
        }

        return $this->parseRoutes($path);
    }

    private function parseRoutes($path)
    {
        $contents = file_get_contents($path);
        if (empty($contents)) {
            throw new \RuntimeException(sprintf('The routing file "%s" is empty.', $path));
        }

        $data = $this->doParseContents($path, $contents);

        if (empty($data) || empty($data['routes'])) {
            throw new \RuntimeException(sprintf('The routing file "%s" is empty or invalid.', $path));
        }

        $routes = new RouteCollection();
        foreach ($data['routes'] as $name => $route) {
            $this->checkRoute($name, $route);
            $routes->add($name, $this->parseRoute($name, $route));
        }

        return $routes;
    }

    private function parseRoute($name, array $route)
    {
        return new Route(
            $this->parseRoutePath($route, $name),
            $this->parseRouteParams($route, $name),
            $this->parseMethods($route, $name),
            $this->parseRouteRequirements($route, $name)
        );
    }

    private function parseRoutePath(array $route, $name)
    {
        $path = $route['path'][0];
        if ('/' !== substr($path, 0, 1)) {
            throw new \RuntimeException(sprintf('The route path "%s" for route "%s" is not valid.', $path, $name));
        }

        return $path;
    }

    private function parseMethods(array $route, $name)
    {
        if (!array_key_exists('methods', $route)) {
            return [];
        }

        if (empty($route['methods'])) {
            throw new \RuntimeException(sprintf('Route methods constraint for route "%s" must not be empty when set.', $name));
        }

        if (is_array($route['methods'])) {
            return $route['methods'];
        }

        if (!preg_match('/^[A-Z]+$/', $route['methods'])) {
            throw new \RuntimeException(sprintf('Route method constraint for route "%s" must be a single HTTP method as a string or an array of HTTP methods.', $name));
        }

        return array($route['methods']);
    }

    private function parseRouteParams(array $route, $name)
    {
        if (!isset($route['params'])) {
            return [];
        }

        if (!is_array($route['params'])) {
            throw new \RuntimeException(sprintf('Route parameters for route "%s" must be defined as an associative array.', $name));
        }

        return $route['params'];
    }

    private function parseRouteRequirements(array $route, $name)
    {
        if (!isset($route['requirements'])) {
            return [];
        }

        if (!is_array($route['requirements'])) {
            throw new \RuntimeException(sprintf('Route requirements for route "%s" must be defined as an associative array.', $name));
        }

        return $route['requirements'];
    }

    private function checkRoute($name, $route)
    {
        if (!is_array($route) || empty($route)) {
            throw new \RuntimeException(sprintf('Route definition "%s" cannot be empty.', $name));
        }

        if (!preg_match(self::VALID_ROUTE_NAME_PATTERN, $name)) {
            throw new \RuntimeException(sprintf('Route name "%s" contains invalid characters.', $name));
        }

        foreach ($route as $key => $value) {
            static::checkRouteKey($name, $key);
        }

        if (empty($route['path'])) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }
    }

    private static function checkRouteKey($name, $key)
    {
        if (!in_array($key, static::$allowedRouteKeys)) {
            throw new \RuntimeException(sprintf('Key "%s" for route "%s" is not supported. Allowed keys are %s.', $key, $name, implode(', ', static::$allowedRouteKeys)));
        }
    }
}
