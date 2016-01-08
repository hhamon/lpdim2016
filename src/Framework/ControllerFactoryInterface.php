<?php

namespace Framework;

interface ControllerFactoryInterface
{
    /**
     * Creates an invokable controller.
     *
     * @param array $params
     * @return \callable
     */
    public function createController(array $params);
}
