<?php

namespace Framework;

use Framework\Http\Request;
use Framework\Http\Response;

class ExceptionEvent extends KernelEvent
{
    private $exception;

    public function __construct(\Exception $exception, Request $request, Response $response = null)
    {
        parent::__construct($request, $response);

        $this->exception = $exception;
    }

    public function getException()
    {
        return $this->exception;
    }
}
