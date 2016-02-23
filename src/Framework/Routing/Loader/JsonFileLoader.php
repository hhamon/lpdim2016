<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 31/01/2016
 * Time: 16:50
 */

namespace Framework\Routing\Loader;


use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

class JsonFileLoader implements FileLoaderInterface
{

    /**
     * Loads a routing configuration file.
     *
     * @param string $path The configuration file path
     * @return RouteCollection
     */
    public function load($path)
    {
        if ('json' != pathinfo($path, PATHINFO_EXTENSION)) {
            throw new UnsupportedFileTypeException(sprintf(
                'File %s must be a valid Json file.',
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

    /**
     * Parse all routes
     * @param $path
     * @return RouteCollection
     */
    private function parseRoutes($path)
    {
        $routes = new RouteCollection();

        $json = json_decode(file_get_contents($path));

        foreach ($json->routes as $name => $route) {
            $this->parseRoute($routes, $route, $name);
        }
        return $routes;
    }

    private function parseRoute(RouteCollection $routes, \stdClass $route, $name)
    {
        if (empty($name) || "_empty_" == $name) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        if (empty($route->path)) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        if (empty($route->methods)) {
            throw new \RuntimeException(sprintf('Route %s must have a method.', $name));
        }

        $params = $this->parseRouteParams($route, $name);
        $requirements = $this->parseRouteRequirements($route, $name);

        $routes->add($name, new Route((string) $route->path, $params, $route->methods, $requirements));
    }

    /**
     * Parse params default for a given route
     * @param \stdClass $route
     * @param $name
     * @return array
     */
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

    /**
     * Parse a single route param
     * @param $name
     * @param $param
     * @param $key
     * @return array
     */
    private function parseRouteParam($name, $param, $key)
    {
        if (empty($param)) {
            throw new \RuntimeException(sprintf(
                'Parameter #%u for route %s mustn\'t be empty.',
                $key,
                $name
            ));
        }

        return [ (string) $key => (string) $param ];
    }

    /**
     * Parse all requirements for a given route
     * @param \stdClass $route
     * @param $name
     * @return array
     */
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

    /**
     * Parse a single requirement
     * @param $name
     * @param $requirement
     * @param $key
     * @return array
     */
    private function parseRouteRequirement($name, $requirement, $key)
    {
        if (empty($requirement)) {
            throw new \RuntimeException(sprintf(
                'Requirement #%u for route %s mustn\'t be empty.',
                $key,
                $name
            ));
        }

        return [ (string) $key => (string) $requirement ];
    }
}