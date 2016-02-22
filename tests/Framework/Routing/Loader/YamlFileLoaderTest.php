<?php

namespace Tests\Framework\Routing\Loader;


use Framework\Routing\Loader\YamlFileLoader;
use Framework\Routing\RouteCollection;

class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
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
        $loader->load('foo.yml');
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
            [ 'invalid1.yml' ],
            [ 'invalid2.yml' ],
            [ 'invalid3.yml' ],
            [ 'invalid4.yml' ],
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