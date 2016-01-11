<?php

namespace Application;

use Framework\ExceptionEvent;
use Framework\Http\HttpNotFoundException;
use Framework\Routing\MethodNotAllowedException;
use Framework\Routing\RouteNotFoundException;
use Psr\Log\LoggerInterface;

class LoggerHandler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        $message = $exception->getMessage();

        if ($exception instanceof HttpNotFoundException) {
            return $this->logger->error($message);
        }

        if ($exception instanceof RouteNotFoundException) {
            return $this->logger->error($message);
        }

        if ($exception instanceof MethodNotAllowedException) {
            return $this->logger->error($message);
        }

        $this->logger->critical($message);
    }
}
