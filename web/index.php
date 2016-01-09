<?php

require_once __DIR__.'/../bootstrap.php';

use Framework\Http\Request;
use Framework\Http\StreamableInterface;
use Framework\Kernel;

$serviceLocator = require __DIR__.'/../bootstrap.php';

$kernel = new Kernel($serviceLocator);
$response = $kernel->handle(Request::createFromGlobals());

if ($response instanceof StreamableInterface) {
    $response->send();
}
