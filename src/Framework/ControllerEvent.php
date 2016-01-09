<?php

namespace Framework;

use Framework\Http\Request;

class ControllerEvent extends KernelEvent
{
    private $controller;

    public function __construct(callable $controller, Request $request)
    {
        parent::__construct($request);

        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }
}
