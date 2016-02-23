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

        $json = json_decode(file_get_contents($path), true);
        foreach ($json['routes'] as $key => $route) {
            $this->parseRoute($routes, $route, $key);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, $route, $key)
    {
        if (empty($key)) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        //var_dump($route);die;

        $name = (string) $key;
        if (empty($route['path'])) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        $methods = [];
        if (!empty($route['methods'])) {
            foreach ($route['methods'] as $method) {
                array_push($methods, $method);
            }
        }

        $params = $this->parseRouteParams($route, $name);
        $requirements = $this->parseRouteRequirements($route, $name);

        $routes->add($name, new Route((string) $route['path'], $params, $methods, $requirements));
    }

    private function parseRouteParams($route, $name)
    {
        //var_dump($route);die;
        $params = [];
        if (!count($route['defaults'])) {
            return $params;
        }
        //var_dump($route[$name]['defaults']);die;
        foreach ($route['defaults'] as $position => $param) {
            $params = array_merge($params, $this->parseRouteParam($name, $route['defaults'], $position));
        }

        return $params;
    }

    private function parseRouteParam($name, $param, $position)
    {
        //var_dump($param);die;
        if (empty($param)) {
            throw new \RuntimeException(sprintf(
                'Parameter #%u for route %s must have a "key" attribute.',
                $position,
                $name
            ));
        }
        $key = key($param);
        return [ (string) $key => (string) $param[$key] ];
    }

    private function parseRouteRequirements($route, $name)
    {
        $requirements = [];
        if (empty($route['requirements'])) {
            return $requirements;
        }

        foreach ($route['requirements'] as $position => $requirement) {
            $requirements = array_merge($requirements, $this->parseRouteRequirement($name, $requirement, $position));
        }

        return $requirements;
    }

    private function parseRouteRequirement($name, $requirement, $position)
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
