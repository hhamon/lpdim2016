<?php

namespace Tests\Framework\Renderer;

use Framework\Http\Response;
use Framework\Templating\TwigRendererAdapter;

class TwigRendererAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Templating\TemplateNotFoundException
     */
    public function testRenderNonExistentTemplate()
    {
        $this->createRenderer()->renderResponse('not-found.twig');
    }

    public function testRenderViewResponse()
    {
        $response = $this
            ->createRenderer()
            ->renderResponse('hello.twig', ['name' => 'Hugo'])
        ;

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains($this->getExpectedOutput(), $response->getBody());
    }

    public function testRenderView()
    {
        $this->assertContains(
            $this->getExpectedOutput(),
            $this->createRenderer()->render('hello.twig', ['name' => 'Hugo'])
        );
    }

    private function createRenderer()
    {
        return new TwigRendererAdapter(
            new \Twig_Environment(
                new \Twig_Loader_Filesystem(__DIR__.'/Fixtures')
            )
        );
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

    protected function setUp()
    {
        if (!class_exists('Twig_Environment')) {
            $this->markTestSkipped('The twig/twig package is required.');
        }
    }
}
