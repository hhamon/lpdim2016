<?php

namespace Framework\Routing\Loader;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

class XmlFileLoader implements FileLoaderInterface
{
    public function load($path)
    {
        if ('xml' !== pathinfo($path, PATHINFO_EXTENSION)) {
            throw new UnsupportedFileTypeException(sprintf(
                'File %s must be a valid XML file.',
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

        $xml = new \SimpleXMLElement(file_get_contents($path));
        foreach ($xml->route as $route) {
            $this->parseRoute($routes, $route);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, \SimpleXMLElement $route)
    {
        if (empty($route['name'])) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        $name = (string) $route['name'];
        if (empty($route['path'])) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        $methods = [];
        if (!empty($route['methods'])) {
            $methods = explode('|', $route['methods']);
        }

        $params = $this->parseRouteParams($route, $name);

        $routes->add($name, new Route((string) $route['path'], $params, $methods));
    }

    private function parseRouteParams(\SimpleXMLElement $route, $name)
    {
        $params = [];
        if (!$route->count()) {
            return $params;
        }

        foreach ($route->param as $position => $param) {
            $params = array_merge($params, $this->parseRouteParam($name, $param, $position));
        }

        return $params;
    }

    private function parseRouteParam($name, \SimpleXMLElement $param, $position)
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
}
