<?php

namespace Framework\Routing;

interface UrlMatcherInterface
{
    /**
     * Matches a url pattern with a set of attributes.
     *
     * @param RequestContext $context The request context
     *
     * @return array An array of attributes
     */
    public function match(RequestContext $context);
}
