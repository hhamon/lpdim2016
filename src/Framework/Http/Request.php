<?php

namespace Framework\Http;

class Request extends AbstractMessage implements RequestInterface, AttributeHolderInterface
{
    private $method;
    private $path;
    private $attributes;

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
        parent::__construct($scheme, $schemeVersion, $headers, $body);

        $this->attributes = [];
        $this->setMethod($method);
        $this->path = $path;
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

    private static function parsePrologue($message)
    {
        $lines = explode(PHP_EOL, $message);
        $result = preg_match('#^(?P<method>[A-Z]{3,7}) (?P<path>.+) (?P<scheme>HTTPS?)\/(?P<version>[1-2]\.[0-2])$#', $lines[0], $matches);
        if (!$result) {
            throw new MalformedHttpMessageException($message, 'HTTP message prologue is malformed.');
        }

        return $matches;
    }

    final public static function createFromMessage($message)
    {
        if (!is_string($message) || empty($message)) {
            throw new MalformedHttpMessageException($message, 'HTTP message is not valid.');
        }

        // 1. Parse prologue (first required line)
        $prologue = static::parsePrologue($message);

        // 4. Construct new instance of Request class with atomic data
        return new self(
            $prologue['method'],
            $prologue['path'],
            $prologue['scheme'],
            $prologue['version'],
            static::parseHeaders($message),
            static::parseBody($message)
        );
    }

    public static function createFromGlobals()
    {
        $protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
        $path = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

        return new self($_SERVER['REQUEST_METHOD'], $path, $protocol[0], $protocol[1]);
    }

    public static function create($method, $path, array $headers = [], $body = '')
    {
        $protocol = [ static::HTTP, static::VERSION_1_1 ];
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
        }

        return new self($method, $path, $protocol[0], $protocol[1], $headers, $body);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    protected function createPrologue()
    {
        return sprintf('%s %s %s/%s', $this->method, $this->path, $this->scheme, $this->schemeVersion);
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        if ($this->hasAttribute($name)) {
            return $this->attributes[$name];
        }

        return $default;
    }

    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }
}
