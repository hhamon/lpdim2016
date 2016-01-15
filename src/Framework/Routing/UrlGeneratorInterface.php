<?php

namespace Framework\Routing;

interface UrlGeneratorInterface
{
    const RELATIVE_URL = 0;
    const ABSOLUTE_URL = 1;

    /**
     * Generates a relative or absolute URL based on routing configuration.
     *
     * @param string $name   The route name
     * @param array  $params The route parameters to replace tokens
     * @param int    $type   Whether or not to generate an absolute URL
     */
    public function generate($name, array $params = [], $type = self::RELATIVE_URL);
}
