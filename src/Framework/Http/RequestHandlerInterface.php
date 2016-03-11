<?php

namespace Framework\Http;

interface RequestHandlerInterface
{
    /**
     * Handles a Request object.
     *
     * @param RequestInterface $request
     *
     * @return void
     */
    public function handleRequest(RequestInterface $request);
}
