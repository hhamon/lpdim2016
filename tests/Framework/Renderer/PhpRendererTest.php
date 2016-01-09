<?php

namespace Tests\Framework\Renderer;

use Framework\Http\Response;
use Framework\Templating\PhpRenderer;

class PhpRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Templating\TemplateNotFoundException
     */
    public function testRenderNonExistentTemplate()
    {
        $this->createRenderer()->render('not-found.php');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRejectNonStringVariableToEscape()
    {
        $this->createRenderer()->e(true);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRejectReservedKeywordsInViewVariables()
    {
        $this
            ->createRenderer()
            ->render('hello.php', ['view' => 'wow'])
        ;
    }

    public function testRenderViewResponse()
    {
        $response = $this
            ->createRenderer()
            ->renderResponse('hello.php', ['name' => 'Hugo'])
        ;

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains($this->getExpectedOutput(), $response->getBody());
    }

    public function testRenderView()
    {
        $this->assertContains(
            $this->getExpectedOutput(),
            $this->createRenderer()->render('hello.php', ['name' => 'Hugo'])
        );
    }

    private function createRenderer()
    {
        return new PhpRenderer(__DIR__.'/Fixtures');
    }

    private function getExpectedOutput()
    {
        $message = <<<OUTPUT
<p>
    Hello Hugo!
</p>
OUTPUT;

        return $message;
    }
}
