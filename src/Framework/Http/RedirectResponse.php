<?php

namespace Framework\Http;

class RedirectResponse extends Response
{
    /**
     * Constructor.
     *
     * @param string $url         The path to redirect to
     * @param int    $statusCode  The response status code (one of 3XX)
     * @param array  $headers     The response headers list
     * @param string $scheme      The response scheme (HTTP or HTTPS)
     * @param string $version     The response scheme version (1.0 or 1.1)
     */
    public function __construct($url, $statusCode = 302, array $headers = [], $scheme = self::HTTP, $version = self::VERSION_1_1)
    {
        $statusCode = (int) $statusCode;
        if ($statusCode < 300 || $statusCode > 308) {
            throw new \InvalidArgumentException(sprintf(
                '$statusCode %u must be a valid integer between 300 and 308.',
                $statusCode
            ));
        }

        $headers = array_merge($headers, [ 'Location' => $url ]);

        parent::__construct($statusCode, $scheme, $version, $headers, '');
    }
}
