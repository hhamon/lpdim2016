<?php

require_once __DIR__.'/../vendor/autoload.php';

use Framework\Http\Request;
use Framework\Http\StreamableInterface;
use Framework\Kernel;
use Framework\ControllerFactory;
use Framework\Routing\Router;
use Framework\Routing\Loader\CompositeFileLoader;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\Loader\XmlFileLoader;
use Framework\ServiceLocator\ServiceLocator;
use Framework\Templating\BracketRenderer;
use Framework\Templating\PhpRenderer;
use Framework\Templating\TwigRendererAdapter;

$dic = new ServiceLocator();
$dic->setParameter('router.file', __DIR__.'/../app/config/routes.xml');
$dic->setParameter('app.views_dir', __DIR__.'/../app/views');
$dic->setParameter('twig.options', [
    'cache' => __DIR__.'/../app/cache/twig',
    'debug' => true,
]);

$dic->register('twig', function (ServiceLocator $dic) {
    return new \Twig_Environment(
        new \Twig_Loader_Filesystem($dic->getParameter('app.views_dir')),
        $dic->getParameter('twig.options')
    );
});

$dic->register('renderer', function (ServiceLocator $dic) {
    return new TwigRendererAdapter($dic->getService('twig'));
});

$dic->register('router', function (ServiceLocator $dic) {
    $loader = new CompositeFileLoader();
    $loader->add(new PhpFileLoader());
    $loader->add(new XmlFileLoader());

    return new Router($dic->getParameter('router.file'), $loader);
});

$dic->register('controller_factory', function () {
    return new ControllerFactory();
});

$kernel = new Kernel($dic);
$response = $kernel->handle(Request::createFromGlobals());

if ($response instanceof StreamableInterface) {
    $response->send();
}
