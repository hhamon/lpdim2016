<?php

namespace Framework;

use Framework\Http\RedirectResponse;
use Framework\Http\Response;
use Framework\Routing\UrlGeneratorInterface;
use Framework\ServiceLocator\ServiceLocatorInterface;

abstract class AbstractAction
{
    /**
     * The dependency injection container.
     *
     * @var ServiceLocatorInterface
     */
    private $dic;

    /**
     * Sets the service container.
     *
     * @param ServiceLocatorInterface $dic
     */
    public function setServiceLocator(ServiceLocatorInterface $dic)
    {
        $this->dic = $dic;
    }

    /**
     * Returns a global service container parameter.
     *
     * @param string $key The configuration parameter name
     *
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->dic->getParameter($key);
    }

    /**
     * Returns a service from the service container.
     *
     * @param string $name The service name
     *
     * @return object
     */
    protected function getService($name)
    {
        return $this->dic->getService($name);
    }

    /**
     * Creates a Response object with a template.
     *
     * @param string $view The template path to render
     * @param array  $vars The template variables
     *
     * @return Response
     */
    protected function render($view, array $vars = [])
    {
        return $this->getService('renderer')->renderResponse($view, $vars);
    }

    /**
     * Generates a new URL based on a route definition.
     *
     * @param string $url        The URI to redirect to
     * @param int    $statusCode The response status code
     *
     * @return RedirectResponse
     */
    protected function redirect($url, $statusCode = Response::HTTP_FOUND)
    {
        return new RedirectResponse($url, $statusCode);
    }

    /**
     * Redirects to a URL generated based on a route definition.
     *
     * @param string $route      The route name
     * @param array  $params     The URL parameters
     * @param int    $type       The URL type (relative or absolute)
     * @param int    $statusCode The response status code
     *
     * @return RedirectResponse
     */
    protected function redirectToRoute($route, array $params = [], $type = UrlGeneratorInterface::RELATIVE_URL, $statusCode = Response::HTTP_FOUND)
    {
        return $this->redirect($this->generateUrl($route, $params, $type), $statusCode);
    }

    /**
     * Generates a new URL based on a route definition.
     *
     * @param string $route  The route name
     * @param array  $params The URL parameters
     * @param int    $type   The URL type (relative or absolute)
     *
     * @return string The generated URL
     */
    protected function generateUrl($route, array $params = [], $type = UrlGeneratorInterface::RELATIVE_URL)
    {
        return $this->getService('url_generator')->generate($route, $params, $type);
    }
}
