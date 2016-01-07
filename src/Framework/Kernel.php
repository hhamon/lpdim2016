<?php

namespace Framework;

use Framework\Http\RequestInterface;
use Framework\Http\Response;
use Framework\Http\ResponseInterface;
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
        $response = null;

        try {
            $params = $this->router->match($request->getPath());
        } catch (RouteNotFoundException $e) {
            return new Response(
                404,
                $request->getScheme(),
                $request->getSchemeVersion(),
                [],
                'Page Not Found'
            );
        }

        if (!empty($params['_controller'])) {
            $action = new $params['_controller']();
            $response = call_user_func_array($action, [ $request ]);
        }

        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException('A response instance must be set.');
        }

        return $response;
    }
}
