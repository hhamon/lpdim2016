<?php

namespace Framework\Routing;

use Framework\Http\RequestInterface;

class RequestContext
{
    const HTTP_PORT = 80;
    const HTTPS_PORT = 443;

    private $path;
    private $method;
    private $port;
    private $domain;
    private $script;
    private $scheme;

    public function __construct($method, $path, $domain, $scheme = 'http', $port = 80, $script = '')
    {
        $this->path = $path;
        $this->method = $method;
        $this->port = (int) $port;
        $this->domain = $domain;
        $this->scheme = $scheme;
        $this->script = $script;
    }

    public static function createFromRequest(RequestInterface $request)
    {
        return new self(
            $request->getMethod(),
            $request->getPath(),
            $request->getDomain(),
            $request->getScheme(),
            $request->getPort(),
            $request->getScriptName()
        );
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

    public function getPort()
    {
        return $this->port;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getScript()
    {
        return $this->script;
    }

    public function getScheme()
    {
        return $this->scheme;
    }
}
