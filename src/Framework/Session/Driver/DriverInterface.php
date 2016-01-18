<?php

namespace Framework\Session\Driver;

interface DriverInterface
{
    /**
     * Clear session data.
     *
     * @param string $id The unique session ID
     *
     * @return bool True if successful, false otherwise
     *
     * @throws DriverException
     */
    public function clear($id);

    /**
     * Stores new data into the session.
     *
     * @param string $id    The unique session ID
     * @param string $key   The session variable name
     * @param mixed  $value The session variable value (must be serializable at some point)
     *
     * @return bool True if succeeded, false otherwise.
     *
     * @throws DriverException
     */
    public function store($id, $key, $value);

    /**
     * Fetches data from the session.
     *
     * @param string $id      The unique session ID
     * @param string $key     The session variable name
     * @param mixed  $default The default value to return
     *
     * @return mixed|null
     *
     * @throws DriverException
     */
    public function fetch($id, $key, $default = null);

    /**
     * Saves the session data to the persistent storage.
     *
     * @param string $id The unique session ID
     *
     * @return bool True if succeeded, false otherwise.
     *
     * @throws DriverException
     */
    public function save($id);
}
