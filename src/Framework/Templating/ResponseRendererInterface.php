<?php

namespace Framework\Templating;

use Framework\Http\Response;
use Framework\Http\ResponseInterface;

interface ResponseRendererInterface extends RendererInterface
{
    /**
     * Evaluates a template view file and returns a Response instance.
     *
     * @param string $view       The template filename
     * @param array  $vars       The view variables
     * @param int    $statusCode The response status code
     *
     * @return Response
     */
    public function renderResponse($view, array $vars = [], $statusCode = ResponseInterface::HTTP_OK);
}
