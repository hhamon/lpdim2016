<?php

require_once __DIR__.'/vendor/autoload.php';

use Application\Repository\BlogPostRepository;
use Framework\ControllerFactory;
use Framework\Routing\Router;
use Framework\Routing\Loader\CompositeFileLoader;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\Loader\XmlFileLoader;
use Framework\ServiceLocator\ServiceLocator;
use Framework\Templating\TwigRendererAdapter;
use Symfony\Component\Yaml\Yaml;

$settings = Yaml::parse(file_get_contents(__DIR__.'/app/config/settings.yml'));

$dic = new ServiceLocator($settings['parameters']);
$dic->setParameter('database.options', [
    \PDO::ATTR_AUTOCOMMIT => false,
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
]);
$dic->setParameter('router.file', __DIR__.'/app/config/routes.xml');
$dic->setParameter('app.views_dir', __DIR__.'/app/views');
$dic->setParameter('twig.options', [
    'cache' => __DIR__.'/../app/cache/twig',
    'debug' => true,
]);

$dic->register('repository.blog_post', function (ServiceLocator $dic) {
    return new BlogPostRepository($dic->getService('database'));
});

$dic->register('database', function (ServiceLocator $dic) {
    return new \PDO(
        $dic->getParameter('database.dsn'),
        $dic->getParameter('database.user'),
        $dic->getParameter('database.password'),
        $dic->getParameter('database.options')
    );
});

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

return $dic;
