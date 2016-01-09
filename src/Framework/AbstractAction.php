<?php

namespace Framework;

use Framework\Http\RedirectResponse;
use Framework\Http\Response;
use Framework\ServiceLocator\ServiceLocatorInterface;

abstract class AbstractAction
{
    /**
     * The dependency injection container.
     *
     * @var ServiceLocatorInterface
     */
    private $dic;

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

    protected function redirect($url, $statusCode = Response::HTTP_FOUND)
    {
        return new RedirectResponse($url, $statusCode);
    }
}
