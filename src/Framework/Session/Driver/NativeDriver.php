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
        $_SESSION = [];
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

    /**
     * Could fetch and unset a key
     * @param $id
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function fetchAndUnset($id, $key, $default)
    {
        $value = $this->fetch($id,$key,$default);
        unset($_SESSION[$this->namespace][$key]);
        return $value;
    }

    private function doFetch($id, $key)
    {
        if(!isset($_SESSION[$this->namespace])){
            return null;
        }
        if (array_key_exists($key, $_SESSION[$this->namespace])) {
            return $_SESSION[$this->namespace][$key];
        }
    }

    public function save($id)
    {
        return true;
    }
}
