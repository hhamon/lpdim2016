<?php

namespace Application;

use Framework\ExceptionEvent;
use Framework\Http\RedirectResponse;
use Framework\Http\Response;
use Framework\Routing\UrlGeneratorInterface;
use Framework\Security\Authentication\AuthenticationRequiredException;
use Framework\Security\Authorization\AccessDeniedException;
use Framework\Templating\ResponseRendererInterface;

class SecurityErrorHandler
{
    private $urlGenerator;
    private $responseRenderer;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ResponseRendererInterface $responseRenderer
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->responseRenderer = $responseRenderer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof AuthenticationRequiredException) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('login')));
            return;
        }

        $vars = [
            'exception' => $exception,
            'request' => $event->getRequest(),
        ];

        if ($exception instanceof AccessDeniedException) {
            $event->setResponse($this->render('errors/403.twig', $vars, Response::HTTP_FORBIDDEN));
        }
    }

    private function render($view, array $vars, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->responseRenderer->renderResponse($view, $vars, $statusCode);
    }
}
