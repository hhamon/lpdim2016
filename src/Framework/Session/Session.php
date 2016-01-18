<?php

namespace Framework\Session;

use Framework\Session\Driver\DriverInterface;

class Session implements SessionInterface
{
    private $id;
    private $driver;
    private $options;
    private $started;
    private $destroyed;
    private $testMode;

    public function __construct(DriverInterface $driver, array $options = [], $testMode = false)
    {
        if (!empty($options['session.auto_start'])) {
            throw new \RuntimeException('Session auto start must be disabled.');
        }

        if (!empty($options['session.id'])) {
            $this->id = $options['session.id'];
            unset($options['session.id']);
        }

        $this->driver = $driver;
        $this->options = array_merge(self::getDefaultOptions(), $options);
        $this->started = false;
        $this->destroyed = false;
        $this->testMode = (bool) $testMode;
    }

    private static function getDefaultOptions()
    {
        return [
            'session.name' => 'PHPSESSID',
            'session.save_path' => '',
            'session.auto_start' => 0,
            'session.gc_probability' => 1,
            'session.gc_divisor' => 100,
            'session.gc_maxlifetime' => 1200,
            'session.use_cookies' => 1,
            'session.use_only_cookies' => 1,
            'session.cookie_lifetime' => 3600,
            'session.cookie_path' => '/',
            'session.cookie_domain' => '',
            'session.cookie_secure' => 0,
            'session.cookie_httponly' => 1,
            'session.cache_limiter' => 'nocache',
            'session.cache_expire' => 180,
            'session.use_trans_sid' => 0,
            'session.lazy_write' => 1,
        ];
    }

    public function start()
    {
        if ($this->destroyed) {
            throw new \RuntimeException('Session has already been destroyed!');
        }

        if ($this->testMode) {
            $this->started = true;
        }

        if (!$this->started) {
            $this->configure();
            $this->started = session_start();
        }

        return $this->started;
    }

    private function configure()
    {
        // Configure session name
        if ($name = $this->getOption('name')) {
            session_name($name);
        }

        // Configure session save path
        $path = $this->getOption('save_path');
        if (!empty($path)) {
            session_save_path($path);
        }

        // Force session ID to be regenerated to
        // prevent session hijacking attack
        if (session_regenerate_id(true)) {
            $this->id = session_id($this->id);
        }

        // Configure more options here
        // ...

        // Configure session cookie
        session_set_cookie_params(
            $this->getOption('cookie_lifetime'),
            $this->getOption('cookie_path'),
            $this->getOption('cookie_domain'),
            $this->getOption('cookie_secure'),
            $this->getOption('cookie_httponly')
        );
    }

    private function getOption($name)
    {
        $key = 'session.'.$name;
        if (!array_key_exists($key, $this->options)) {
            throw new \InvalidArgumentException(sprintf('Key %s does not exist in options array.', $key));
        }

        return $this->options[$key];
    }

    public function destroy()
    {
        $this->start();
        $this->driver->clear($this->id);

        if ($this->testMode) {
            return $this->destroyed = true;
        }

        setcookie($this->getOption('name'));
        $this->destroyed = session_destroy();

        return $this->destroyed;
    }

    public function store($key, $value)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Session variable name must be a valid string.');
        }

        $this->start();

        return $this->driver->store($this->id, $key, $value);
    }

    public function fetch($key, $default = null)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Session variable name must be a valid string.');
        }

        $this->start();

        return $this->driver->fetch($this->id, $key, $default);
    }

    public function getId()
    {
        return $this->id;
    }

    public function save()
    {
        $this->start();

        return $this->driver->save($this->id);
    }
}
