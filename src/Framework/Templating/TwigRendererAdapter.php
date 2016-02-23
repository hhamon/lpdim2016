<?php

namespace Framework\Templating;

use Framework\Http\Response;
use Framework\Http\ResponseInterface;
use Twig_Extensions_Extension_Text;

class TwigRendererAdapter implements ResponseRendererInterface
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->twig->addExtension(new Twig_Extensions_Extension_Text());
    }

    public function render($view, array $vars = [])
    {
        try {
            return $this->twig->render($view, $vars);
        } catch (\Twig_Error_Loader $e) {
            throw new TemplateNotFoundException(
                sprintf('Template "%s" cannot be found by Twig.', $view),
                0,
                $e
            );
        }
    }

    public function renderResponse($view, array $vars = [], $statusCode = ResponseInterface::HTTP_OK)
    {
        return new Response($statusCode, 'HTTP', '1.1', [], $this->render($view, $vars));
    }
}
