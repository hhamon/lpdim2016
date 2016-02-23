<?php

namespace Tests\Framework\Routing\Loader;

use Framework\Routing\Loader\JSONFileLoader;
use Framework\Routing\RouteCollection;

class JSONFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Routing\Loader\UnsupportedFileTypeException
     */
    public function testLoadUnsupportedFileType()
    {
        $loader = new JSONFileLoader();
        $loader->load('foo.txt');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadNonExistentFile()
    {
        $loader = new JSONFileLoader();
        $loader->load('foo.xml');
    }

    /**
     * @expectedException \RuntimeException
     * @dataProvider provideInvalidFile
     */
    public function testLoadInvalidFile($file)
    {
        $loader = new JSONFileLoader();
        $loader->load(__DIR__.'/../Fixtures/'.$file);
    }

    public function provideInvalidFile()
    {
        return [
            [ 'invalid1.json' ],
            [ 'invalid2.json' ],
            [ 'invalid3.json' ],
            [ 'invalid4.json' ],
        ];
    }

    public function testLoadFile()
    {
        $loader = new JSONFileLoader();

        $this->assertInstanceOf(
            RouteCollection::class,
            $loader->load(__DIR__.'/../Fixtures/routes.json')
        );
    }
}
