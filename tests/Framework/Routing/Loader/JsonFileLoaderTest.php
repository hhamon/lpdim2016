<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 31/01/2016
 * Time: 17:08
 */

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
     * @dataProvider provideInvalidFile
     */
    public function testLoadInvalidFile($file)
    {
        $loader = new JsonFileLoader();
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
        $loader = new JsonFileLoader();

        $this->assertInstanceOf(
            RouteCollection::class,
            $loader->load(__DIR__.'/../Fixtures/routes.json')
        );
    }
}