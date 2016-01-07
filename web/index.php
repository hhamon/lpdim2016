<?php

require_once __DIR__.'/../vendor/autoload.php';

use Framework\Http\Request;
use Framework\Http\StreamableInterface;
use Framework\Kernel;
use Framework\Routing\Router;
use Framework\Routing\Loader\CompositeFileLoader;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\Loader\XmlFileLoader;

$loader = new CompositeFileLoader();
$loader->add(new PhpFileLoader());
$loader->add(new XmlFileLoader());

$router = new Router(__DIR__.'/../config/routes.xml', $loader);
$kernel = new Kernel($router);

$response = $kernel->handle(Request::createFromGlobals());

if ($response instanceof StreamableInterface) {
    $response->send();
}
