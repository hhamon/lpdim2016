<?php

namespace Framework;

class ClosureControllerFactory implements ControllerFactoryInterface
{
    /**
     * Creates an invokable controller.
     *
     * @param array $params
     * @return \callable
     */
    public function createController(array $params)
    {
        if (!empty($params['_controller']) && $params['_controller'] instanceof \Closure) {
            return $params['_controller'];
        }

        throw new \RuntimeException('NO CONTROLLER FOUND!');
    }
}
