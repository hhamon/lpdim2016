<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 23/02/2016
 * Time: 22:09
 */

namespace Test\Framework\Routing;

use Framework\Routing\Loader\CompositeFileLoader;
use Framework\Routing\Loader\XmlFileLoader;
use Framework\Routing\UrlGenerator;
use Framework\Routing\UrlGeneratorInterface;

class UrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGenerateWithMissingParams()
    {
        $generator = $this->createGenerator();
        $generator->generate('blog_post');
    }

    public function testGenerateGoodUrl()
    {
        $generator = $this->createGenerator();
        $url = $generator->generate('blog_post', ['id' => '42']);
        $this->assertEquals('/index.php/blog/article-42.html',$url);
    }

    public function testGenerateUrlWithPage()
    {
        $generator = $this->createGenerator();
        $url = $generator->generate('blog_post', ['id' => '42', 'page' => 2]);
        $this->assertEquals('/index.php/blog/article-42.html?page=2',$url);
    }

    public function testGenerateUrlWithAbsolutePath()
    {
        $generator = $this->createGenerator();
        $generator->setTest(true);
        $generator->setDomain('mydomain.com');
        $url = $generator->generate('blog_post', ['id' => '42'], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->assertEquals('http://mydomain.com/index.php/blog/article-42.html',$url);
    }

    public function testGenerateAbsoluteUrlWithPage()
    {
        $generator = $this->createGenerator();
        $generator->setTest(true);
        $generator->setDomain('mydomain.com');
        $url = $generator->generate('blog_post', ['id' => '42', 'page' => 2], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->assertEquals('http://mydomain.com/index.php/blog/article-42.html?page=2',$url);
    }

    private function createGenerator()
    {
        $loader = new CompositeFileLoader();
        $loader->add(new XmlFileLoader());
        $generator = new UrlGenerator(__DIR__.'/Fixtures/route-to-generate.xml',$loader);
        return $generator;
    }
}