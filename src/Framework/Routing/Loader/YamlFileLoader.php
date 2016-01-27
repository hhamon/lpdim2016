<?php

namespace Framework\Routing\Loader;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;


class YamlFileLoader implements FileLoaderInterface
{

    /**
     * Loads a routing configuration file.
     *
     * @param string $path The configuration file path
     * @return RouteCollection
     */
    public function load($path)
    {
        if ('yml' !== pathinfo($path, PATHINFO_EXTENSION) && 'yaml' !== pathinfo($path, PATHINFO_EXTENSION)) {
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

        try {
            $yml = Yaml::parse(file_get_contents($path));
        } catch (ParseException $e) {
            throw new \InvalidArgumentException(sprintf('The file "%s" is not a valid YAML.', $path));
        }



        foreach ($yml as $name => $route) {
            $this->parseRoute($routes, $name, $route);
        }

        return $routes;
    }

    private function parseRoute(RouteCollection $routes, $name, $route)
    {
        if (empty($name)) {
            throw new \RuntimeException('Each route must have a unique name.');
        }

        if (empty($route['path'])) {
            throw new \RuntimeException(sprintf('Route %s must have a path.', $name));
        }

        $methods = [];
        if (!empty($route['methods'])) {
            $methods = explode('|', $route['methods']);
        }

        $params = [];
        if (!empty($route['default'])) {
            $params = $route['default'];
        }

        $requirements = [];
        if (!empty($route['requirements'])) {
            $requirements = $route['requirements'];
        }


        $routes->add($name, new Route($route['path'], $params, $methods, $requirements));
    }





}
