<?php

namespace Tests\Framework;

use Framework\DefaultControllerNameParser;
use Framework\ShortNotationControllerNameParser;
use Tests\Framework\Fixtures\Controller\Blog\BlogAction;
use Tests\Framework\Fixtures\Controller\DemoAction;

class ShortNotationControllerNameParserTest extends \PHPUnit_Framework_TestCase
{
    public function testNoMatchingNamespaceForShortNotation()
    {
        $parser = $this->createParser();

        $this->setExpectedException('RuntimeException');
        $parser->getClass('FooBar:Blog:Blog');
    }

    public function testGetClassFromRealClass()
    {
        $parser = $this->createParser();

        $this->assertSame(DemoAction::class, $parser->getClass(DemoAction::class));
    }

    /**
     * @dataProvider provideShortNotation
     */
    public function testGetClassFromShortNotation($class, $notation)
    {
        $parser = $this->createParser();

        $this->assertSame($class, $parser->getClass($notation));
    }

    public function provideShortNotation()
    {
        return [
            [DemoAction::class, 'AppTest:Demo'],
            [BlogAction::class, 'AppTest:Blog:Blog'],
        ];
    }

    private function createParser()
    {
        return new ShortNotationControllerNameParser(new DefaultControllerNameParser(), [
            'AppTest' => 'Tests\\Framework\\Fixtures\\Controller',
        ]);
    }
}
