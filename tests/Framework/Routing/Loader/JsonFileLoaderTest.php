<?php

namespace Tests\Framework\Routing\Loader;

use Framework\Routing\Loader\JsonFileLoader;
use Framework\Routing\RouteCollection;

class JsonFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    private static function createLoader()
    {
        return new JsonFileLoader();
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
        static::createLoader()->load('foo.json');
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
            [ 'invalid1.json' ],
            [ 'invalid2.json' ],
            [ 'invalid3.json' ],
            [ 'invalid4.json' ],
            [ 'invalid5.json' ],
            [ 'invalid6.json' ],
            [ 'invalid7.json' ],
            [ 'invalid8.json' ],
            [ 'invalid9.json' ],
            [ 'invalid10.json' ],
            [ 'invalid11.json' ],
        ];
    }

    public function testLoadFile()
    {
        $routes = static::createLoader()->load(__DIR__.'/../Fixtures/routes.json');

        $this->assertInstanceOf(RouteCollection::class, $routes);
        $this->assertCount(6, $routes);
    }
}
