<?php

namespace Framework;

use Framework\Http\RequestInterface;
use Framework\Http\Response;
use Framework\Http\ResponseInterface;

class Kernel implements KernelInterface
{
    /**
     * Converts a Request object into a Response object.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request)
    {
        return new Response(
            Response::HTTP_OK,
            $request->getScheme(),
            $request->getSchemeVersion(),
            [ 'Content-Type' => 'application/json'],
            json_encode([ 'Hello' => 'World!' ])
        );
    }
}
