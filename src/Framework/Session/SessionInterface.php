<?php

namespace Framework\Session;

interface SessionInterface
{
    /**
     * Starts the session.
     *
     * @return bool True if the session is already started, false otherwise
     */
    public function start();

    /**
     * Destroys the session.
     *
     * @return bool True if the session was correctly destroyed, false otherwise.
     */
    public function destroy();

    /**
     * Stores new data into the session.
     *
     * @param string $key   The session variable name
     * @param mixed  $value The session variable value (must be serializable at some point)
     *
     * @return bool True if succeeded, false otherwise.
     */
    public function store($key, $value);

    /**
     * Fetches a data from the session.
     *
     * @param string $key     The session variable name
     * @param mixed  $default The default value to return
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null);

    /**
     * Returns the session's unique identifier.
     *
     * @return string
     */
    public function getId();

    /**
     * Saves the session data to the persistent storage.
     *
     * @return bool True if successful, false otherwise
     */
    public function save();
}
