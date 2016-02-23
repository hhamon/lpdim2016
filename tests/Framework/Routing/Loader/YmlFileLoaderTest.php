<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 27/01/2016
 * Time: 20:19
 */

namespace Tests\Framework\Routing\Loader;

use Framework\Routing\Loader\YamlFileLoader;
use Framework\Routing\RouteCollection;

class YmlFileLoaderTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \InvalidArgumentException
     */
    public function testLoadInvalidYmlFile()
    {
        $loader = new YamlFileLoader();
        $loader->load(__DIR__.'/../Fixtures/invalid3.yml');
    }


    public function testLoadFile()
    {
        $loader = new YamlFileLoader();

        $loaded = $loader->load(__DIR__.'/../Fixtures/routes.yml');

        $this->assertInstanceOf(
            RouteCollection::class,
            $loaded
        );
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
        ];
    }


}