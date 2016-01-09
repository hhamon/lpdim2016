<?php

namespace tests\Framework\Routing\Loader;

use Framework\Routing\Loader\CompositeFileLoader;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\Loader\XmlFileLoader;
use Framework\Routing\RouteCollection;

class CompositeFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Routing\Loader\UnsupportedFileTypeException
     */
    public function testCannotLoadFile()
    {
        $loader = $this->createLoader();

        $this->assertInstanceOf(
            RouteCollection::class,
            $loader->load(__DIR__.'/../Fixtures/invalid.yml')
        );
    }

    /**
     * @dataProvider provideFile
     */
    public function testLoadFile($file)
    {
        $loader = $this->createLoader();

        $this->assertInstanceOf(
            RouteCollection::class,
            $loader->load(__DIR__.'/../Fixtures/'.$file)
        );
    }

    public function provideFile()
    {
        return [
            [ 'routes.php' ],
            [ 'routes.xml' ],
        ];
    }

    private function createLoader()
    {
        $loader = new CompositeFileLoader();
        $loader->add(new PhpFileLoader());
        $loader->add(new XmlFileLoader());

        return $loader;
    }
}
