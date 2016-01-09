<?php

namespace Tests\Framework;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\KernelInterface;

class DummyHttpKernel implements KernelInterface
{
    public function handle(Request $request)
    {
        return Response::createFromRequest($request, 'DUMMY!');
    }
}
