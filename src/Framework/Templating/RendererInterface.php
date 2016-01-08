<?php

namespace Framework\Templating;

interface RendererInterface
{
    /**
     * Evaluates a template view file.
     *
     * @param string $view The template filename
     * @param array  $vars The view variables
     *
     * @return string
     *
     * @throws TemplateNotFoundException When template does not exist
     */
    public function render($view, array $vars = []);
}
