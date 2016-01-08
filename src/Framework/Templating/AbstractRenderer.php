<?php

namespace Framework\Templating;

use Framework\Http\Response;
use Framework\Http\ResponseInterface;

abstract class AbstractRenderer implements ResponseRendererInterface
{
    private $directory;

    public function __construct($directory)
    {
        if (!is_dir($directory)) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" does not exist.', $directory));
        }

        $this->directory = realpath($directory);
    }

    protected function getTemplatePath($view)
    {
        $path = $this->directory.DIRECTORY_SEPARATOR.$view;
        if (!is_readable($path)) {
            throw new TemplateNotFoundException(sprintf(
                'Template "%s" cannot be found in "%s" directory.',
                $view,
                $this->directory
            ));
        }

        return $path;
    }

    /**
     * Evaluates a template view file and returns a Response instance.
     *
     * @param string $view The template filename
     * @param array  $vars The view variables
     * @param int    $statusCode The response status code
     *
     * @return Response
     */
    public function renderResponse($view, array $vars = [], $statusCode = ResponseInterface::HTTP_OK)
    {
        return new Response($statusCode, 'HTTP', '1.1', [], $this->render($view, $vars));
    }
}
