<?php

namespace Tests\Framework;

use Framework\AbstractAction;
use Framework\Http\RedirectResponse;
use Framework\Http\Response;
use Framework\Http\ResponseInterface;
use Framework\ServiceLocator\ServiceLocator;
use Framework\Templating\ResponseRendererInterface;

class AbstractActionTest extends \PHPUnit_Framework_TestCase
{
    public function testShortcutProtectedMethod()
    {
        $renderer = $this->getMock(ResponseRendererInterface::class);
        $renderer->expects($this->once())->method('renderResponse')->willReturn(new Response(200, 'HTTP', '1.1', [], 'RESPONSE'));

        $dic = new ServiceLocator(['foo' => 'bar']);
        $dic->register('qux', function () {
            return new \stdClass();
        });
        $dic->register('renderer', function () use ($renderer) {
            return $renderer;
        });

        $action = new Action();
        $action->setServiceLocator($dic);

        $this->assertInstanceOf(Response::class, $action->render('foo/bar.twig', [ 'foo' => 'bar' ]));
        $this->assertInstanceOf(RedirectResponse::class, $action->redirect('http:/foo.com'));
        $this->assertInstanceOf('stdClass', $action->getService('qux'));
        $this->assertSame('bar', $action->getParameter('foo'));
    }
}

class Action extends AbstractAction
{
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this, $name], $arguments);
    }
}

