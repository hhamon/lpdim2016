<?php

namespace Framework;

use Framework\Http\Request;
use Framework\Http\Response;

interface KernelInterface
{
    /**
     * Converts a Request object into a Response object.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request);
}
