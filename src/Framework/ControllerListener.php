<?php

namespace Framework;

use Framework\ServiceLocator\ServiceLocatorInterface;

class ControllerListener
{
    private $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if ($controller instanceof AbstractAction) {
            $controller->setServiceLocator($this->serviceLocator);
        }
    }
}
