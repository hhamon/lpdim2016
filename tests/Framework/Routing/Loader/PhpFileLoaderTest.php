<?php

namespace Tests\Framework\Routing\Loader;

use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\RouteCollection;

class PhpFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Routing\Loader\UnsupportedFileTypeException
     */
    public function testLoadUnsupportedFileType()
    {
        $loader = new PhpFileLoader();
        $loader->load('foo.txt');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadNonExistentFile()
    {
        $loader = new PhpFileLoader();
        $loader->load('foo.php');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testLoadInvalidFile()
    {
        $loader = new PhpFileLoader();
        $loader->load(__DIR__.'/../Fixtures/invalid.php');
    }

    public function testLoadFile()
    {
        $loader = new PhpFileLoader();

        $this->assertInstanceOf(
            RouteCollection::class,
            $loader->load(__DIR__.'/../Fixtures/routes.php')
        );
    }
}
