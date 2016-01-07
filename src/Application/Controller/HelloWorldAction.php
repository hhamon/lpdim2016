<?php

namespace Application\Controller;

use Framework\Http\RequestInterface;
use Framework\Http\Response;

final class HelloWorldAction
{
    public function __invoke(RequestInterface $request)
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
