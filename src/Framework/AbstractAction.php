<?php

namespace Framework;

use Framework\Http\RequestInterface;
use Framework\ServiceLocator\ServiceLocatorInterface;

abstract class AbstractAction
{
    /**
     * The dependency injection container.
     *
     * @var ServiceLocatorInterface
     */
    private $dic;

    abstract public function __invoke(RequestInterface $request);

    public function setServiceLocator(ServiceLocatorInterface $dic)
    {
        $this->dic = $dic;
    }

    protected function getParameter($key)
    {
        return $this->dic->getParameter($key);
    }

    protected function getService($name)
    {
        return $this->dic->getService($name);
    }

    protected function render($view, array $vars)
    {
        return $this->getService('renderer')->renderResponse($view, $vars);
    }
}
