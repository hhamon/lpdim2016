<?php

namespace Framework\ServiceLocator;

class ServiceLocator implements ServiceLocatorInterface
{
    private $parameters;
    private $definitions;
    private $services;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->definitions = [];
        $this->services = [];
    }

    public function setParameter($key, $value)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Parameter key must be a valid string.');
        }

        $this->parameters[$key] = $value;

        return $this;
    }

    public function getParameter($key)
    {
        if (!isset($this->parameters[$key])) {
            throw new \InvalidArgumentException(sprintf(
                'No parameter found for key "%s".',
                $key
            ));
        }

        return $this->parameters[$key];
    }

    public function register($name, \Closure $definition)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('Service name must be a valid string.');
        }

        $this->definitions[$name] = $definition;

        return $this;
    }

    public function getService($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        if (!isset($this->definitions[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'No registered service definition called "%s" found.',
                $name
            ));
        }

        $this->services[$name] = $service = call_user_func_array(
            $this->definitions[$name],
            [ $this ]
        );

        return $service;
    }
}
