<?php
/**
 * Created by PhpStorm.
 * User: leosauvaget
 * Date: 23/02/2016
 * Time: 19:26
 */

namespace Framework;


class ControllerFactoryAdapter implements ControllerFactoryInterface
{
    private $controllerFactoryToAdapt;


    public function __construct(ControllerFactoryInterface $controllerFactoryToAdapt)
    {
        $this->controllerFactoryToAdapt = $controllerFactoryToAdapt;
    }


    public function createController(array $params)
    {
        if (empty($params['_controller'])) {
            throw new \RuntimeException('No _controller parameter found.');
        }

        $class = $params['_controller'];
        $newParams = ['_controller' => $this->reformatControllerPath($class)];
        return $this->controllerFactoryToAdapt->createController($newParams);
    }


    private function reformatControllerPath($class)
    {

        if (!preg_match("/Action$/", $class)) {
            $class = $class . "Action";
        }

        if (preg_match("/App:/", $class)) {
            $class = preg_replace("/^App:/", "Application:", $class);
        }

        if(preg_match("/:/", $class)){
            $class = preg_replace("/:/", "\\", $class);
        }

        if (!preg_match("/Controller/", $class)) {
            $class = preg_replace("/Application/", "Application\\Controller", $class);
            return $class;
        }

        return $class;
    }
}