<?php

namespace Framework\Routing;

use Framework\Http\RequestInterface;

class RequestContext
{
    private $path;
    private $method;

    public function __construct($method, $path)
    {
        $this->path = $path;
        $this->method = $method;
    }

    public static function createFromRequest(RequestInterface $request)
    {
        return new self($request->getMethod(), $request->getPath());
    }

    public function __toString()
    {
        return sprintf('%s %s', $this->method, $this->path);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
