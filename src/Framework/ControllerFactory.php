<?php

namespace Framework;

class ControllerFactory implements ControllerFactoryInterface
{
    /**
     * Creates an invokable controller.
     *
     * @param array $params
     * @return \callable
     */
    public function createController(array $params)
    {
        if (empty($params['_controller'])) {
            throw new \RuntimeException('No _controller parameter found.');
        }

        $class = $params['_controller'];


        $class = $this->reformatControllerPath($class);


        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Controller class "%s" does not exist or cannot be autoloaded.', $class));
        }

        $action = new $class();
        if (!method_exists($action, '__invoke')) {
            throw new \RuntimeException('Controller is not a valid PHP callable object. Make sure the __invoke() method is implemented!');
        }

        return $action;
    }


    /**
     * @param $class
     * @return mixed|string
     */
    private function reformatControllerPath($class)
    {

        if(preg_match('#^Application\x5cController(.+)Action$#', $class)){
            return $class;
        }


        if (!preg_match("/Action$/", $class)) {
            $class = $class . "Action";
        }

        if (preg_match("/App:/", $class)) {
            $class = preg_replace("/^App:/", "Application:", $class);
            $class = preg_replace("/:/", "\\", $class);
        }

        if (!preg_match("/Controller/", $class)) {
            $class = preg_replace("/Application/", "Application\\Controller", $class);
            return $class;
        }

        return $class;
    }
}
