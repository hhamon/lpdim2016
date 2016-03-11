<?php

namespace Tests\Framework\Fixtures\Controller;

use Framework\Http\Request;
use Framework\Http\Response;

class DemoAction
{
    public function __invoke(Request $request)
    {
        return new Response(200, 'http', '1.1', [], 'DEMO');
    }
}
