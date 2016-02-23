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

        //if it's a short Class
        if(preg_match("/App\:/",$class)){
            $class = $this->reformatClass($class);
        }

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
     * @return string
     */
    private function reformatClass($class)
    {
        //explode the string class
        $parts = explode(':',$class);
        array_shift($parts);
        $class = "Application\\Controller\\";
        //iterate with each parts and add a namespace separator
        foreach ($parts as $part) {
            $class .= sprintf('%s\\',$part);
            array_shift($parts);
        }
        //remove parasits
        $class = trim($class,'\\');
        return $class."Action";
    }
}
