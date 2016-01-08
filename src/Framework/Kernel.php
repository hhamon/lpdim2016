<?php

namespace Framework;

use Framework\Http\RequestInterface;
use Framework\Http\Response;
use Framework\Http\ResponseInterface;
use Framework\Routing\MethodNotAllowedException;
use Framework\Routing\RequestContext;
use Framework\Routing\RouteNotFoundException;
use Framework\Routing\RouterInterface;
use Framework\Templating\ResponseRendererInterface;

class Kernel implements KernelInterface
{
    private $router;
    private $controllers;
    private $renderer;

    public function __construct(
        RouterInterface $router,
        ControllerFactoryInterface $controllers,
        ResponseRendererInterface $renderer
    )
    {
        $this->router = $router;
        $this->controllers = $controllers;
        $this->renderer = $renderer;
    }

    /**
     * Converts a Request object into a Response object.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request)
    {
        try {
            return $this->doHandle($request);
        } catch (RouteNotFoundException $e) {
            return $this->renderer->renderResponse('errors/404.twig', [ 'request' => $request, 'exception' => $e ], Response::HTTP_NOT_FOUND);
        } catch (MethodNotAllowedException $e) {
            return $this->createResponse($request, 'Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        } catch (\Exception $e) {
            return $this->createResponse($request, 'Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function doHandle(RequestInterface $request)
    {
        $context = RequestContext::createFromRequest($request);
        $action = $this->controllers->createController($this->router->match($context));

        if ($action instanceof AbstractAction) {
            $action->setRenderer($this->renderer);
        }

        $response = call_user_func_array($action, [ $request ]);

        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException('A controller must return a Response object.');
        }

        return $response;
    }

    private function createResponse(RequestInterface $request, $content, $statusCode = ResponseInterface::HTTP_OK)
    {
        return Response::createFromRequest($request, $content, $statusCode);
    }
}
