<?php

require_once __DIR__.'/../vendor/autoload.php';

use Framework\Http\Request;
use Framework\Http\StreamableInterface;
use Framework\Kernel;

$protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
$request = new Request($_SERVER['REQUEST_METHOD'], $path, $protocol[0], $protocol[1]);

$kernel = new Kernel();
$response = $kernel->handle($request);

if ($response instanceof StreamableInterface) {
    $response->send();
}

