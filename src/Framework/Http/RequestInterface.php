<?php

namespace Framework\Http;

interface RequestInterface extends MessageInterface
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const OPTIONS = 'OPTIONS';
    const CONNECT = 'CONNECT';
    const TRACE = 'TRACE';
    const HEAD = 'HEAD';
    const DELETE = 'DELETE';

    public function getMethod();
    public function isMethod($method);
    public function getPath();
    public function getDomain();
    public function getPort();
    public function getScriptName();
    public function getRequestParameter($name, $default = null);
}
