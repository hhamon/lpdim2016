<?php

namespace Tests\Framework\Renderer;

use Framework\Http\Response;
use Framework\Templating\BracketRenderer;

class BracketRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Templating\TemplateNotFoundException
     */
    public function testRenderNonExistentTemplate()
    {
        $this->createRenderer()->render('not-found.tpl');
    }

    public function testRenderViewResponse()
    {
        $response = $this
            ->createRenderer()
            ->renderResponse('hello.tpl', ['name' => 'Hugo'])
        ;

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains($this->getExpectedOutput(), $response->getBody());
    }

    public function testRenderView()
    {
        $this->assertContains(
            $this->getExpectedOutput(),
            $this->createRenderer()->render('hello.tpl', ['name' => 'Hugo'])
        );
    }

    private function createRenderer()
    {
        return new BracketRenderer(__DIR__.'/Fixtures');
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
