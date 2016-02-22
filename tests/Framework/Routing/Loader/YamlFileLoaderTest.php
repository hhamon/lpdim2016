<?php

namespace Tests\Framework\Routing\Loader;

namespace Tests\Framework\Routing\Loader;
use Framework\Routing\Loader\YamlFileLoader;
use Framework\Routing\RouteCollection;

class XmlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Routing\Loader\UnsupportedFileTypeException
     */
    public function testLoadUnsupportedFileType()
    {
        $loader = new YamlFileLoader();
        $loader->load('foo.txt');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadNonExistentFile()
    {
        $loader = new YamlFileLoader();
        $loader->load('foo.xml');
    }

    /**
     * @expectedException \RuntimeException
     * @dataProvider provideInvalidFile
     */
    public function testLoadInvalidFile($file)
    {
        $loader = new YamlFileLoader();
        $loader->load(__DIR__.'/../Fixtures/'.$file);
    }

    public function provideInvalidFile()
    {
        return [
            [ 'invalid1.xml' ],
            [ 'invalid2.xml' ],
        ];
    }

    public function testLoadFile()
    {
        $loader = new YamlFileLoader();

        $this->assertInstanceOf(
            RouteCollection::class,
            $loader->load(__DIR__.'/../Fixtures/routes.yml')
        );
    }
}
