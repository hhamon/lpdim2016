<?php

namespace Framework\Http;

class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const OPTIONS = 'OPTIONS';
    const CONNECT = 'CONNECT';
    const TRACE = 'TRACE';
    const HEAD = 'HEAD';
    const DELETE = 'DELETE';

    const HTTP = 'HTTP';
    const HTTPS = 'HTTPS';

    const VERSION_1_0 = '1.0';
    const VERSION_1_1 = '1.1';
    const VERSION_2_0 = '2.0';

    private $method;
    private $scheme;
    private $schemeVersion;
    private $path;
    private $headers;
    private $body;

    /**
     * Constructor.
     *
     * @param string $method        The HTTP verb
     * @param string $path          The resource path on the server
     * @param string $scheme        The protocole name (HTTP or HTTPS)
     * @param string $schemeVersion The scheme version (ie: 1.0, 1.1 or 2.0)
     * @param array  $headers       An associative array of headers
     * @param string $body          The request content
     */
    public function __construct($method, $path, $scheme, $schemeVersion, array $headers = [], $body = '')
    {
        $this->headers = [];

        $this->setMethod($method);
        $this->path = $path;
        $this->setScheme($scheme);
        $this->setSchemeVersion($schemeVersion);
        $this->setHeaders($headers);
        $this->body = $body;
    }

    private function setMethod($method)
    {
        $methods = [ 
            self::GET,
            self::POST,
            self::PUT,
            self::PATCH,
            self::OPTIONS,
            self::CONNECT,
            self::TRACE,
            self::HEAD,
            self::DELETE,
        ];

        if (!in_array($method, $methods)) {
            throw new \InvalidArgumentException(sprintf(
                'Method %s is not supported and must be one of %s.',
                $method,
                implode(', ', $methods)
            ));
        }

        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getSchemeVersion()
    {
        return $this->schemeVersion;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    private function setScheme($scheme)
    {
        $schemes = [ self::HTTP, self::HTTPS ];
        if (!in_array($scheme, $schemes)) {
            throw new \InvalidArgumentException(sprintf(
                'Scheme %s is not supported and must be one of %s.',
                $scheme,
                implode(', ', $schemes)
            ));
        }

        $this->scheme = $scheme;
    }

    private function setSchemeVersion($version)
    {
        $versions = [ self::VERSION_1_0, self::VERSION_1_1, self::VERSION_2_0 ];
        if (!in_array($version, $versions)) {
            throw new \InvalidArgumentException(sprintf(
                'Scheme version %s is not supported and must be one of %s.',
                $version,
                implode(', ', $versions)
            ));
        }

        $this->schemeVersion = $version;
    }

    private function setHeaders(array $headers)
    {
        foreach ($headers as $header => $value) {
            $header = strtolower($header);

            if (isset($this->headers[$header])) {
                throw new \RuntimeException(sprintf(
                    'Header %s is already defined and cannot be set twice.',
                    $header
                ));
            }

            $this->headers[$header] = $value;
        }
    }

    public function getHeader($name)
    {
        $name = strtolower($name);

        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }
}
