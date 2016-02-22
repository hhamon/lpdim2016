<?php

namespace Framework\Routing\Loader;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader implements FileLoaderInterface
{
    public function load($path)
    {
        if ('yml' !== pathinfo($path, PATHINFO_EXTENSION)) {
            throw new UnsupportedFileTypeException(sprintf(
                'File %s must be a valid Yaml file.',
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

        $routeArray = Yaml::parse(file_get_contents($path));

        foreach ($routeArray as $name=>$route) {
            $this->parseRoute($routes, $route, $name);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, $route, $name)
    {
        if (!isset($name)) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        if (empty($route['path'])) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        $methods = [];
        if (!empty($route['methods'])) {
            $methods = $route['methods'];
        }

        $requirements = [];

        $routes->add($name, new Route((string) $route['path'], $route['defaults'], $methods, $requirements));
    }

    private function parseRouteRequirements(\SimpleXMLElement $route, $name)
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

    private function parseRouteRequirement($name, \SimpleXMLElement $requirement, $position)
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
