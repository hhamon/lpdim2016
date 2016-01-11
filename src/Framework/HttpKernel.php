<?php

namespace Framework;

use Framework\EventManager\Event;
use Framework\EventManager\EventManagerInterface;
use Framework\Http\Request;
use Framework\Http\Response;

class HttpKernel implements KernelInterface
{
    private $eventManager;
    private $factory;

    public function __construct(EventManagerInterface $eventManager, ControllerFactoryInterface $factory)
    {
        $this->eventManager = $eventManager;
        $this->factory = $factory;
    }

    final public function handle(Request $request)
    {
        try {
            $response = $this->doHandleRequest($request);
        } catch (\Exception $exception) {
            $response = $this->doHandleException($request, $exception);
        }

        return $this->doHandleResponse($request, $response);
    }

    private function doHandleException(Request $request, \Exception $exception)
    {
        $event = $this->dispatch(KernelEvents::EXCEPTION, new ExceptionEvent($exception, $request));
        if (!$event->hasResponse()) {
            throw new \RuntimeException('No exception handler has generated a Response', 0, $exception);
        }

        return $event->getResponse();
    }

    private function doHandleRequest(Request $request)
    {
        $event = $this->dispatch(KernelEvents::REQUEST, new KernelEvent($request));

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        // Create the controller based on request attributes
        $controller = $this->factory->createController($request->getAttributes());

        $event = $this->dispatch(KernelEvents::CONTROLLER, new ControllerEvent($controller, $request));

        $response = call_user_func_array($event->getController(), [ $request ]);

        if (!$response instanceof Response) {
            throw new \RuntimeException('A controller must return a Response object.');
        }

        return $response;
    }

    private function doHandleResponse(Request $request, Response $response)
    {
        $this->dispatch(KernelEvents::RESPONSE, new KernelEvent($request, $response));

        return $response;
    }

    private function dispatch($name, Event $event)
    {
        return $this->eventManager->dispatch($name, $event);
    }
}
