<?php

namespace Application;

use Framework\ExceptionEvent;
use Framework\Http\HttpNotFoundException;
use Framework\Http\Response;
use Framework\Routing\MethodNotAllowedException;
use Framework\Routing\RouteNotFoundException;
use Framework\Templating\ResponseRendererInterface;

class ErrorHandler
{
    private $environment;
    private $debug;
    private $renderer;

    public function __construct(ResponseRendererInterface $renderer, $environment = 'dev', $debug = false)
    {
        $this->renderer = $renderer;
        $this->environment = $environment;
        $this->debug = (bool) $debug;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        $vars = [
            'exception' => $exception,
            'request' => $request,
            'environment' => $this->environment,
            'debug' => $this->debug,
        ];

        if ($exception instanceof RouteNotFoundException) {
            $event->setResponse($this->render('errors/404.twig', $vars, Response::HTTP_NOT_FOUND));
            return;
        }

        if ($exception instanceof HttpNotFoundException) {
            $event->setResponse($this->render('errors/404.twig', $vars, Response::HTTP_NOT_FOUND));
            return;
        }

        if ($exception instanceof MethodNotAllowedException) {
            $event->setResponse($this->render('errors/405.twig', $vars, Response::HTTP_METHOD_NOT_ALLOWED));
            return;
        }

        $event->setResponse($this->render('errors/500.twig', $vars));
    }

    private function render($view, array $vars, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->renderer->renderResponse($view, $vars, $statusCode);
    }
}
