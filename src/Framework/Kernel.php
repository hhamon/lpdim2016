<?php

namespace Framework;

use Framework\Http\RequestInterface;
use Framework\Http\Response;
use Framework\Http\ResponseInterface;
use Framework\Routing\MethodNotAllowedException;
use Framework\Routing\RequestContext;
use Framework\Routing\RouteNotFoundException;
use Framework\Routing\RouterInterface;

class Kernel implements KernelInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
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
            return $this->createResponse($request, 'Page Not Found', Response::HTTP_NOT_FOUND);
        } catch (MethodNotAllowedException $e) {
            return $this->createResponse($request, 'Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        } catch (\Exception $e) {
            return $this->createResponse($request, 'Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function doHandle(RequestInterface $request)
    {
        $context = RequestContext::createFromRequest($request);

        $params = $this->router->match($context);
        if (empty($params['_controller'])) {
            throw new \RuntimeException(sprintf('No controller set for "%s".', (string) $context));
        }

        $class = $params['_controller'];
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Controller class "%s" does not exist or cannot be autoloaded.', $class));
        }

        $action = new $class();
        if (!is_callable($action)) {
            throw new \RuntimeException('Controller is not a valid PHP callable object. Make sure the __invoke() method is implemented!');
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
