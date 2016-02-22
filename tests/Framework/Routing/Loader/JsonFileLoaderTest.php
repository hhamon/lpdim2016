<?php

namespace Tests\Framework\Routing\Loader;

use Framework\Routing\Loader\JsonFileLoader;
use Framework\Routing\RouteCollection;

class JsonFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Routing\Loader\UnsupportedFileTypeException
     */
    public function testLoadUnsupportedFileType()
    {
        $loader = new JsonFileLoader();
        $loader->load('foo.txt');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadNonExistentFile()
    {
        $loader = new JsonFileLoader();
        $loader->load('foo.json');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testLoadInvalidFile()
    {
        $loader = new JsonFileLoader();
        $loader->load(__DIR__.'/../Fixtures/invalid.json');
    }

    public function testLoadFile()
    {
        $loader = new JsonFileLoader();

        $this->assertInstanceOf(
            RouteCollection::class,
            $loader->load(__DIR__.'/../Fixtures/routes.json')
        );
    }
}
