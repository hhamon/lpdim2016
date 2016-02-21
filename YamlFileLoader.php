<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 14/02/2016
 * Time: 19:34
 */

namespace Framework\Routing\Loader;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader implements FileLoaderInterface
{
    public function load($path)
    {
        if (!in_array(pathinfo($path, PATHINFO_EXTENSION),['yaml','yml'])) {
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

        $yaml = Yaml::parse(file_get_contents($path));
        foreach ($yaml->routes as $route) {
            $this->parseRoute($routes, $route);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, array $route)
    {
        if (empty($route['name'])) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        $name = (string) $route['name'];
        if (empty($route['path'])) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        if (empty($route['methods'])) {
            throw new \RuntimeException(sprintf('Route %s must have a method.', $name));
        }

        $params = $this->parseRouteParams($route, $name);
        $requirements = $this->parseRouteRequirements($route, $name);

        $routes->add($name, new Route((string) $route['path'], $params, $route['methods'], $requirements));
    }

    private function parseRouteParams(array $route, $name)
    {
        $params = [];
        if (!isset($route['default']) && !count($route['defaults'])) {
            return $params;
        }
        foreach ($route['defaults'] as $key => $param) {
            $params = array_merge($params, $this->parseRouteParam($name, $param, $key));
        }
        return $params;
    }

    private function parseRouteParam($name, $param, $key)
    {
        if (empty($param)) {
            throw new \RuntimeException(sprintf(
                'Parameter #%u for route %s must not be empty.',
                $key,
                $name
            ));
        }
        return [ (string) $key => (string) $param ];
    }

    private function parseRouteRequirements(array $route, $name)
    {
        $requirements = [];
        if (!isset($route['requirements']) || !count($route['requirements'])) {
            return $requirements;
        }
        foreach ($route['requirements'] as $key => $requirement) {
            $requirements = array_merge($requirements, $this->parseRouteRequirement($name, $requirement, $key));
        }
        return $requirements;
    }

    private function parseRouteRequirement($name, $requirement, $key)
    {
        if (empty($requirement)) {
            throw new \RuntimeException(sprintf(
                'Requirement #%u for route %s must not be empty.',
                $key,
                $name
            ));
        }
        return [ (string) $key => (string) $requirement ];
    }
}