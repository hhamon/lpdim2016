<?php
/**
 * Created by PhpStorm.
 * User: Damien
 * Date: 22/02/2016
 * Time: 19:37
 */

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
                'File %s must be a valid YAML file.',
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

        $yml = Yaml::parse(file_get_contents($path));

        foreach ($yml as $name => $route) {
            $this->parseRoute($routes, $route, $name);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, array $route, $name)
    {
        if (empty($name)) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        if (empty($route['path'])) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        if (empty($route['methods'])) {
            throw new \RuntimeException(sprintf('Route %s must have a method.', $name));
        }

        $methods = $route['methods'];
        $params = $this->parseRouteParams($route, $name);
        $requirements = $this->parseRouteRequirements($route, $name);

        $routes->add($name, new Route((string) $route['path'], $params, $methods, $requirements));
    }

    private function parseRouteParams(array $route, $name)
    {
        $params = [];
        if (!count($route['defaults'])) {
            return $params;
        }

        foreach ($route['defaults'] as $position => $param) {
            $params = array_merge($params, $this->parseRouteParam($name, $param, $position));
        }

        return $params;
    }

    private function parseRouteParam($name, $param, $position)
    {
        if (empty($param)) {
            throw new \RuntimeException(sprintf(
                'Parameter #%u for route %s mustn\'t be empty.',
                $position,
                $name
            ));
        }

        return [ (string) $position => (string) $param ];
    }

    private function parseRouteRequirements(array $route, $name)
    {
        $requirements = [];
        if (!isset($route['requirements']) || !count($route['requirements'])) {
            return $requirements;
        }

        foreach ($route['requirements'] as $position => $requirement) {
            $requirements = array_merge($requirements, $this->parseRouteRequirement($name, $requirement, $position));
        }

        return $requirements;
    }

    private function parseRouteRequirement($name, $requirement, $position)
    {
        if (empty($requirement)) {
            throw new \RuntimeException(sprintf(
                'Requirement #%u for route %s mustn\'t be empty.',
                $position,
                $name
            ));
        }

        return [ (string) $position => (string) $requirement ];
    }
}
