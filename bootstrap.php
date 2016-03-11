<?php

require_once __DIR__.'/vendor/autoload.php';

use Application\Blog\BlogPostRepository;
use Application\ErrorHandler;
use Application\LoggerHandler;
use Application\Twig\RoutingExtension;
use Application\Twig\TextExtension;
use Framework\ControllerFactory;
use Framework\ControllerListener;
use Framework\DefaultControllerNameParser;
use Framework\EventManager\EventManager;
use Framework\HttpKernel;
use Framework\KernelEvents;
use Framework\RouterListener;
use Framework\Routing\Router;
use Framework\Routing\Loader\CompositeFileLoader;
use Framework\Routing\Loader\JsonFileLoader;
use Framework\Routing\Loader\LazyFileLoader;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\Loader\XmlFileLoader;
use Framework\Routing\Loader\YamlFileLoader;
use Framework\Routing\UrlGenerator;
use Framework\ServiceLocator\ServiceLocator;
use Framework\Session\Driver\NativeDriver;
use Framework\Session\Session;
use Framework\ShortNotationControllerNameParser;
use Framework\Templating\TwigRendererAdapter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser as YamlParser;

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
$dic->setParameter('logger.log_file', __DIR__.'/app/cache/app.log');
$dic->setParameter('session.options', [
    'session.name' => 'lpdim2016',
    'session.save_path' => __DIR__.'/app/sessions',
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
    $twig = new \Twig_Environment(
        new \Twig_Loader_Filesystem($dic->getParameter('app.views_dir')),
        $dic->getParameter('twig.options')
    );
    $twig->addExtension(new RoutingExtension($dic->getService('url_generator')));
    $twig->addExtension(new TextExtension());

    return $twig;
});

$dic->register('renderer', function (ServiceLocator $dic) {
    return new TwigRendererAdapter($dic->getService('twig'));
});

$dic->register('router.loader', function (ServiceLocator $dic) {
    $loader = new CompositeFileLoader();
    $loader->add(new PhpFileLoader());
    $loader->add(new XmlFileLoader());
    $loader->add(new YamlFileLoader(new YamlParser()));
    $loader->add(new JsonFileLoader());

    return new LazyFileLoader($dic->getParameter('router.file'), $loader);
});

$dic->register('url_generator', function (ServiceLocator $dic) {
    return new UrlGenerator($dic->getService('router.loader'));
});

$dic->register('router', function (ServiceLocator $dic) {
    return new Router($dic->getService('router.loader'));
});

$dic->register('error_handler', function (ServiceLocator $dic) {
    return new ErrorHandler(
        $dic->getService('renderer'),
        $dic->getParameter('app.environment'),
        $dic->getParameter('app.debug')
    );
});

$dic->register('session', function (ServiceLocator $dic) {
    return new Session(new NativeDriver(), $dic->getParameter('session.options'));
});

$dic->register('logger', function (ServiceLocator $dic) {
    $level = $dic->getParameter('app.debug') ? Logger::WARNING : Logger::CRITICAL;

    $logger = new Logger('blog');
    $logger->pushHandler(new StreamHandler($dic->getParameter('logger.log_file'), $level));

    return $logger;
});

$dic->register('event_manager', function (ServiceLocator $dic) {
    $manager = new EventManager();
    $manager->addEventListener(KernelEvents::REQUEST, [ new RouterListener($dic->getService('router'), $dic->getService('url_generator')), 'onKernelRequest' ]);
    $manager->addEventListener(KernelEvents::CONTROLLER, [ new ControllerListener($dic), 'onKernelController' ]);
    $manager->addEventListener(KernelEvents::EXCEPTION, [ new LoggerHandler($dic->getService('logger')), 'onKernelException' ]);
    $manager->addEventListener(KernelEvents::EXCEPTION, [ $dic->getService('error_handler'), 'onKernelException' ]);

    return $manager;
});

$dic->register('http_kernel', function (ServiceLocator $dic) {
    $factory = new ControllerFactory(new ShortNotationControllerNameParser(new DefaultControllerNameParser(), [
        'App' => 'Application\\Controller',
    ]));

    return new HttpKernel($dic->getService('event_manager'), $factory);
});

return $dic;
