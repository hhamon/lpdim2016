<?php

namespace Application\Controller;

use Framework\Http\RequestInterface;
use Framework\Templating\ResponseRendererInterface;

final class HelloWorldAction
{
    /**
     * The template engine.
     *
     * @var ResponseRendererInterface
     */
    private $renderer;

    public function setRenderer(ResponseRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(RequestInterface $request)
    {
        return $this->renderer->renderResponse('hello.tpl', [ 'name' => 'hugo' ]);
    }
}
