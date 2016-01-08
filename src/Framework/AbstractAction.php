<?php

namespace Framework;

use Framework\Templating\ResponseRendererInterface;

abstract class AbstractAction
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

    protected function render($view, array $vars)
    {
        return $this->renderer->renderResponse($view, $vars);
    }
}
