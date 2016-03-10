<?php

namespace Tests\Framework\Routing\Loader;

use Framework\Routing\Loader\YamlFileLoader;
use Framework\Routing\RouteCollection;
use Symfony\Component\Yaml\Parser as YamlParser;

class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (!class_exists('Symfony\Component\Yaml\Parser')) {
            $this->markTestSkipped('the symfony/yaml package is required to run this test.');
        }
    }

    private static function createLoader()
    {
        return new YamlFileLoader(new YamlParser());
    }

    /**
     * @expectedException \Framework\Routing\Loader\UnsupportedFileTypeException
     */
    public function testLoadUnsupportedFileType()
    {
        static::createLoader()->load('foo.txt');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadNonExistentFile()
    {
        static::createLoader()->load('foo.yml');
    }

    /**
     * @expectedException \RuntimeException
     * @dataProvider provideInvalidFile
     */
    public function testLoadInvalidFile($file)
    {
        static::createLoader()->load(__DIR__.'/../Fixtures/'.$file);
    }

    public function provideInvalidFile()
    {
        return [
            [ 'invalid1.yml' ],
            [ 'invalid2.yml' ],
            [ 'invalid3.yaml' ],
            [ 'invalid4.yaml' ],
            [ 'invalid5.yaml' ],
            [ 'invalid6.yaml' ],
            [ 'invalid7.yaml' ],
            [ 'invalid8.yaml' ],
            [ 'invalid9.yaml' ],
            [ 'invalid10.yaml' ],
            [ 'invalid11.yaml' ],
            [ 'invalid12.yaml' ],
        ];
    }

    public function testLoadFile()
    {
        $routes = static::createLoader()->load(__DIR__.'/../Fixtures/routes.yml');

        $this->assertInstanceOf(RouteCollection::class, $routes);
        $this->assertCount(6, $routes);
    }
}
