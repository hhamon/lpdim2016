<?php

namespace Application\User;

use Framework\Security\User\UserInterface;

class User implements UserInterface
{
    private $id;
    private $username;
    private $salt;
    private $password;
    private $permissions;
    private $registrationDate;

    private function __construct($username, $password, $salt = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->permissions = [];
        $this->registrationDate = date('Y-m-d H:i:s');
    }

    public static function register($username, $password, $salt = null)
    {
        return new self($username, $password, $salt);
    }

    public static function fromArray(array $data)
    {
        if (empty($data['username'])) {
            throw new \LogicException('The username property is missing.');
        }

        if (empty($data['password'])) {
            throw new \LogicException('The password property is missing.');
        }

        if (empty($data['salt'])) {
            throw new \LogicException('The salt property is missing.');
        }

        if (!empty($data['permissions']) && is_string($data['permissions'])) {
            $data['permissions'] = explode(',', $data['permissions']);
        }

        $user = new self($data['username'], $data['password'], $data['salt']);

        if (!empty($data['permissions'])) {
            $user->addPermissions($data['permissions']);
        }

        if (!empty($data['registration_date'])) {
            $user->registrationDate = $data['registration_date'];
        }

        return $user;
    }

    public function __toString()
    {
        return (string) $this->username;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    public function addPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }
    }

    public function addPermission($permission)
    {
        if (!in_array($permission, $this->getPermissions())) {
            $this->permissions[] = $permission;
        }
    }

    public function revokePermission($permission)
    {
        if (false !== $key = array_search($permission, $this->getPermissions())) {
            unset($this->permissions[$key]);
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function changePassword($password, $salt = null)
    {
        $this->password = $password;
        $this->salt = $salt;
    }

    public function getPermissions()
    {
        return (array) $this->permissions;
    }
}
