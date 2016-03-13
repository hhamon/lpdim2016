<?php

namespace Framework\Routing;

use Framework\Routing\Loader\LazyFileLoaderInterface;

class UrlGenerator implements UrlGeneratorInterface, RequestContextAwareInterface
{
    private $context;
    private $loader;

    /**
     * Constructor.
     *
     * @param LazyFileLoaderInterface $loader
     * @param RequestContext|null     $context
     */
    public function __construct(LazyFileLoaderInterface $loader, RequestContext $context = null)
    {
        $this->loader = $loader;
        $this->context = $context;
    }

    /**
     * Generates a relative or absolute URL based on routing configuration.
     *
     * @param string $name The route name
     * @param array  $params The route parameters to replace tokens
     * @param int    $type Whether or not to generate an absolute URL
     *
     * @return string The built URL
     *
     * @throws UrlGenerationException
     */
    public function generate($name, array $params = [], $type = self::RELATIVE_URL)
    {
        if (!$this->context) {
            throw new UrlGenerationException('A RequestContext instance must be set to generate URLs.');
        }

        $route = $this->getRoute($name);

        $url = '';
        if (self::ABSOLUTE_URL === $type) {
            $url.= $this->buildDomainName();
        }

        $url.= $this->context->getScript();
        $url.= $this->buildPathinfo($route, $name, $params);
        $url.= $this->buildQueryString($route, $params);

        return $url;
    }

    /**
     * Sets the RequestContext object.
     *
     * @param RequestContext $context
     */
    public function setRequestContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * Returns the list of all extra parameters to be added as a query string.
     *
     * @param array $tokens The URL placeholders
     * @param array $params The parameters to generate the URL
     *
     * @return array $extras The list of extra parameters for the query string
     */
    private function getExtraParameters(array $tokens, array $params)
    {
        $extras = [];
        foreach ($params as $key => $value) {
            if (!in_array($key, $tokens)) {
                $extras[$key] = $value;
            }
        }

        return $extras;
    }

    /**
     * Returns whether or not the port must be added to the URL.
     *
     * @return bool
     */
    private function needServerPort()
    {
        if ('http' === $this->context->getScheme()
            && RequestContext::HTTP_PORT === $this->context->getPort()) {
            return false;
        }

        if ('https' === $this->context->getScheme()
            && RequestContext::HTTPS_PORT === $this->context->getPort()) {
            return false;
        }

        return true;
    }

    /**
     * Returns the route by its name.
     *
     * @param string $name The route name
     *
     * @return Route
     */
    private function getRoute($name)
    {
        $routes = $this->loader->load();

        try {
            return $routes->getRoute($name);
        } catch (RouteNotFoundException $e) {
            throw new UrlGenerationException(sprintf('There is no route registered with name "%s".', $name));
        }
    }

    /**
     * Builds the pathinfo of a route.
     *
     * @param Route  $route  The route definition
     * @param string $name   The route name
     * @param array  $params The route parameters
     *
     * @return string The pathinfo
     */
    private function buildPathinfo(Route $route, $name, array $params)
    {
        $patterns = [];
        $replacements = [];
        $tokens = $route->getPathTokens();
        foreach ($tokens as $token) {
            $patterns[] = sprintf('{%s}', $token);
            $replacements[] = $this->findTokenValue($route, $name, $params, $token);
        }

        return str_replace($patterns, $replacements, $route->getPath());
    }

    /**
     * Finds the most appropriate token value.
     *
     * @param Route  $route  The route definition
     * @param string $name   The route name
     * @param array  $params The route URL parameters
     * @param string $token  The token name
     *
     * @return bool|string
     */
    private function findTokenValue(Route $route, $name, array $params, $token)
    {
        // Find the token value and check it matches its requirement.
        if (isset($params[$token])) {
            return $this->filterTokenValue($route, $token, $params[$token]);
        }

        try {
            // Otherwise, return the token default value if it exists.
            return $route->getParameter($token);
        } catch (RouteParameterNotFoundException $e) {
            throw new UrlGenerationException(sprintf('Missing value for parameter "%s" to generate a URL for the route "%s".', $token, $name), $e->getCode(), $e);
        }
    }

    /**
     * Returns a URL token value.
     *
     * @param Route  $route The route definition
     * @param string $token The token name
     * @param mixed  $value The token value
     *
     * @return string $value The filtered token value
     *
     * @throws UrlGenerationException
     */
    private function filterTokenValue(Route $route, $token, $value)
    {
        if ($route->matchRequirement($token, $value)) {
            return (string) $value;
        }

        throw new UrlGenerationException(sprintf('Value "%s" for parameter "%s" does not match its requirement "%s".', $value, $token, $route->getRequirement($token)));
    }

    /**
     * Builds the query string.
     *
     * @param Route  $route  The route definition
     * @param array  $params The route parameters
     *
     * @return string The pathinfo
     */
    private function buildQueryString(Route $route, array $params)
    {
        $extras = $this->getExtraParameters($route->getPathTokens(), $params);

        $qs = '';
        if (count($extras)) {
            $qs = '?' . http_build_query($extras);
        }

        return $qs;
    }

    /**
     * Builds the scheme and domain name based on the request context.
     *
     * @return string
     */
    private function buildDomainName()
    {
        return sprintf(
            '%s://%s%s',
            $this->context->getScheme(),
            $this->context->getDomain(),
            $this->needServerPort() ? ':'.$this->context->getPort() : ''
        );
    }
}
