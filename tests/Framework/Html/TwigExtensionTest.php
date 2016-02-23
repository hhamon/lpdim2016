<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 23/02/2016
 * Time: 22:53
 */

namespace Tests\Framework\Html;


use Application\Html\TwigUrlGeneratorExtension;
use Framework\Routing\Loader\CompositeFileLoader;
use Framework\Routing\Loader\XmlFileLoader;
use Framework\Routing\UrlGenerator;
use Framework\Templating\TwigRendererAdapter;

class TwigExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testPathWithParam()
    {
        $response = $this
            ->createRenderer()
            ->renderResponse('path.twig', ['id' => 10,'page'=>2]);
        $this->assertEquals($response->getBody(),'/index.php/blog/article-42.html?page=2');
    }

    public function testUrlWithParam()
    {
        $response = $this
            ->createRenderer()
            ->renderResponse('url.twig', ['id' => 10,'page'=>2]);
        $this->assertEquals($response->getBody(),'http://mydomain.com/index.php/blog/article-42.html?page=2');
    }

    private function createGenerator()
    {
        $loader = new CompositeFileLoader();
        $loader->add(new XmlFileLoader());
        $generator = new UrlGenerator(__DIR__.'/Fixtures/route-to-generate.xml',$loader);
        $generator->setTest(true);
        $generator->setDomain('mydomain.com');
        return $generator;
    }

    public function createRenderer()
    {
        $generator = $this->createGenerator();
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/Fixtures');
        $environment = new \Twig_Environment($loader);
        $environment->addExtension(new TwigUrlGeneratorExtension($generator));
        return new TwigRendererAdapter($environment);
    }
}