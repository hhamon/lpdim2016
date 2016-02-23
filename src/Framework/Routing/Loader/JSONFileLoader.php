<?php

namespace Framework\Routing\Loader;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

class JSONFileLoader implements FileLoaderInterface
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
        foreach ($json->routes as $route) {
          //var_dump($route);
          $this->parseRoute($routes, $route);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, $route)
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
        if (!count($route->params)) {
            return $params;
        }

        foreach ($route->params as $position => $param) {
            $params = array_merge($params, $this->parseRouteParam($name, $param, $position));
        }

        return $params;
    }

    private function parseRouteParam($name, $param, $position)
    {
        if (empty($param['key'])) {
            throw new \RuntimeException(sprintf(
                'Parameter #%u for route %s must have a "key" attribute.',
                $position,
                $name
            ));
        }

        return [ (string) $param['key'] => (string) $param ];
    }

    private function parseRouteRequirements(\stdClass $route, $name)
    {
        $requirements = [];
        if (!count($route->requirement)) {
            return $requirements;
        }

        foreach ($route->requirement as $position => $requirement) {
            $requirements = array_merge($requirements, $this->parseRouteRequirement($name, $requirement, $position));
        }

        return $requirements;
    }

    private function parseRouteRequirement($name, \stdClass $requirement, $position)
    {
        if (empty($requirement['key'])) {
            throw new \RuntimeException(sprintf(
                'Requirement #%u for route %s must have a "key" attribute.',
                $position,
                $name
            ));
        }

        return [ (string) $requirement['key'] => (string) $requirement ];
    }
}
