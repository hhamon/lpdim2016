<?php

namespace Framework\Routing\Loader;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

class JsonFileLoader implements FileLoaderInterface
{
    public function load($path)
    {
        if ('json' !== pathinfo($path, PATHINFO_EXTENSION)) {
            throw new UnsupportedFileTypeException(sprintf(
                'File %s must be a valid JSON file.',
                $path
            ));
        }

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
        $routes = new RouteCollection();
        $json = json_decode(file_get_contents($path));

        foreach ($json->route as $route) {
            $this->parseRoute($routes, $route);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, \stdClass $route)
    {
        if (empty($route->name)) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        $name = (string) $route->name;
        if (empty($route->path)) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        $methods = [];
        if (!empty($route->methods)) {
            $methods = explode('|', $route->methods);
        }

        $params = $this->parseRouteParams($route, $name);
        $requirements = $this->parseRouteRequirements($route, $name);

        $routes->add($name, new Route((string) $route->path, $params, $methods, $requirements));
    }

    private function parseRouteParams(\stdClass $route, $name)
    {
        $params = [];
        if (!isset($route->default) && !count($route->defaults)) {
            return $params;
        }

        foreach ($route->defaults as $key => $param) {
            $params = array_merge($params, $this->parseRouteParam($name, $param, $key));
        }

        return $params;
    }

    private function parseRouteParam($name, $param, $key)
    {
        if (empty($param)) {
            throw new \RuntimeException(sprintf(
                'Parameter #%u for route %s can\'t be empty.',
                $key,
                $name
            ));
        }

        return [ (string) $key => (string) $param ];
    }

    private function parseRouteRequirements(\stdClass $route, $name)
    {
        $requirements = [];
        if (!isset($route->requirements) || !count($route->requirements)) {
            return $requirements;
        }

        foreach ($route->requirements as $key => $requirement) {
            $requirements = array_merge($requirements, $this->parseRouteRequirement($name, $requirement, $key));
        }

        return $requirements;
    }

    private function parseRouteRequirement($name, $requirement, $key)
    {
        if (empty($requirement)) {
            throw new \RuntimeException(sprintf(
                'Requirement #%u for route %s can\'t be empty.',
                $key,
                $name
            ));
        }

        return [ (string) $key => (string) $requirement ];
    }
}
