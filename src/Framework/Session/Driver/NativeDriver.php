<?php

namespace Framework\Session\Driver;

class NativeDriver implements DriverInterface
{
    const DEFAULT_NS = 'FKNS';

    private $namespace;
    private $allowOverride;

    public function __construct($allowOverride = true, $namespace = self::DEFAULT_NS)
    {
        $this->allowOverride = (bool) $allowOverride;
        $this->namespace = $namespace;
        $_SESSION[$namespace] = [];
    }

    public function clear($id)
    {
        $_SESSION[$this->namespace] = [];

        return true;
    }

    public function store($id, $key, $value)
    {
        $fetched = $this->doFetch($id, $key);
        if (null !== $fetched && !$this->allowOverride) {
            throw new DriverException(sprintf('Overriding session variable "%s" is not permitted.', $key));
        }

        $_SESSION[$this->namespace][$key] = $value;

        return true;
    }

    public function fetch($id, $key, $default = null)
    {
        if (null !== $value = $this->doFetch($id, $key)) {
            return $value;
        }

        return $default;
    }

    private function doFetch($id, $key)
    {
        if (array_key_exists($key, (array) $_SESSION[$this->namespace])) {
            return $_SESSION[$this->namespace][$key];
        }
    }

    public function save($id)
    {
        return true;
    }
}
