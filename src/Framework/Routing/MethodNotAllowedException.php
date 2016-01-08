<?php

namespace Framework\Routing;

class MethodNotAllowedException extends \RuntimeException
{
    private $method;
    private $allowedMethods;

    public function __construct($method, array $allowedMethods, \Exception $previous = null)
    {
        $message = sprintf('Method "%s" is not allowed and must be one of "%s".', $method, implode(', ', $allowedMethods));

        parent::__construct($message, 0, $previous);

        $this->method = $method;
        $this->allowedMethods = $allowedMethods;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getAllowedMethods()
    {
        return $this->allowedMethods;
    }
}
