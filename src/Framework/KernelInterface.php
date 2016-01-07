<?php

namespace Framework;

use Framework\Http\RequestInterface;
use Framework\Http\ResponseInterface;

interface KernelInterface
{
    /**
     * Converts a Request object into a Response object.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request);
}
