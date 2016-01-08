<?php

namespace Framework\ServiceLocator;

interface ServiceLocatorInterface
{
    public function setParameter($key, $value);

    public function getParameter($key);

    public function register($name, \Closure $definition);

    public function getService($name);
}
