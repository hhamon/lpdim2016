<?php

namespace Framework\Http;

abstract class AbstractMessage implements MessageInterface
{
    protected $scheme;
    protected $schemeVersion;

    /**
     * A collection of Header instances.
     *
     * @var Header[]
     */
    protected $headers;
    protected $body;

    public function __construct($scheme, $schemeVersion, array $headers = [], $body = '')
    {
        $this->headers = [];
        $this->setScheme($scheme);
        $this->setSchemeVersion($schemeVersion);
        $this->setHeaders($headers);
        $this->body = $body;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getSchemeVersion()
    {
        return $this->schemeVersion;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach ($this->headers as $header) {
            $headers = array_merge($headers, $header->toArray());
        }

        return $headers;
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
            $this->addHeader($header, $value);
        }
    }

    /**
     * Returns the value of the associated header.
     *
     * @param string $name
     * @return string
     */
    public function getHeader($name)
    {
        if ($header = $this->findHeader($name)) {
            return $header->getValue();
        }
    }

    /**
     * Returns the corresponding Header instance.
     *
     * @param string $name
     *
     * @return Header
     */
    private function findHeader($name)
    {
        foreach ($this->headers as $header) {
            if ($header->match($name)) {
                return $header;
            }
        }
    }

    /**
     * Adds a new normalized header value to the list of all headers.
     *
     * @param string $name  The HTTP header name
     * @param string $value The HTTP header value
     *
     * @throws \RuntimeException
     */
    public function addHeader($name, $value)
    { 
        if ($this->findHeader($name)) {
            throw new \RuntimeException(sprintf(
                'Header %s is already defined and cannot be set twice.',
                $name
            ));
        }

        $this->headers[] = new Header($name, (string) $value);
    }

    protected abstract function createPrologue();

    /**
     * Returns the Message instance as an HTTP string representation.
     *
     * @return string
     */
    final public function getMessage()
    {
        $message = $this->createPrologue();

        if (count($this->headers)) {
            $message.= PHP_EOL;
            foreach ($this->headers as $header) {
                $message.= $header.PHP_EOL;
            }
        }

        $message.= PHP_EOL;
        if ($this->body) {
            $message.= $this->body;
        }

        return $message;
    }

    /**
     * String representation of a Message instance.
     *
     * Alias of getMessage().
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

    protected static function parseBody($message)
    {
        $pos = strpos($message, PHP_EOL.PHP_EOL);

        return (string) substr($message, $pos+2);
    }

    protected static function parseHeaders($message)
    {
        $start = strpos($message, PHP_EOL) + 1;
        $end = strpos($message, PHP_EOL.PHP_EOL);
        $length = $end - $start;
        $lines = explode(PHP_EOL, substr($message, $start, $length));

        $i = 0;
        $headers = [];
        while (!empty($lines[$i])) {
            $headers = array_merge($headers, static::parseHeader($lines[$i], $i));
            $i++;
        }

        return $headers;
    }

    private static function parseHeader($line, $position)
    {
        try {
            return Header::createFromString($line)->toArray();
        } catch (MalformedHttpHeaderException $e) {
            throw new MalformedHttpHeaderException(
                sprintf('Invalid header line at position %u: %s', $position+2, $line),
                0,
                $e
            );
        }
    }
}
