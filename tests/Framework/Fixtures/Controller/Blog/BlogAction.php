<?php

namespace Tests\Framework\Fixtures\Controller\Blog;

use Framework\Http\Request;
use Framework\Http\Response;

class BlogAction
{
    public function __invoke(Request $request)
    {
        return new Response(200, 'http', '1.1', [], 'BLOG');
    }
}
