<?php

namespace Framework\Routing;

interface RouterInterface
{
    /**
     * Matches a url pattern with a set of attributes.
     *
     * @param string $path The url pattern to match
     *
     * @return array An array of attributes
     */
    public function match($path);
}
