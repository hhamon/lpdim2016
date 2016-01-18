<?php

namespace Framework\Session\Driver;

/**
 * This class is only used for unit tests.
 *
 * @internal
 */
class ArrayDriver implements DriverInterface
{
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function clear($id)
    {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);

            return true;
        }

        return false;
    }

    public function store($id, $key, $value)
    {
        $this->data[$id][$key] = $value;

        return true;
    }

    public function fetch($id, $key, $default = null)
    {
        if (isset($this->data[$id][$key])) {
            return $this->data[$id][$key];
        }

        return $default;
    }

    public function save($id)
    {
        return true;
    }
}
