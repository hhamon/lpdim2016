<?php

namespace Framework\Security\Authentication;

use Framework\Security\User\UserInterface;

final class SecurityUserToken implements TokenInterface, \Serializable
{
    private $user;
    private $username;
    private $permissions;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
        $this->username = $user->getUsername();
        $this->permissions = $user->getPermissions();
    }

    public function serialize()
    {
        $data = [
            'username' => $this->username,
            'permissions' => $this->permissions,
        ];

        return serialize($data);
    }

    public function unserialize($data)
    {
        $data = unserialize($data);

        $this->username = $data['username'];
        $this->permissions = $data['permissions'];
    }

    public function isAuthenticated()
    {
        return $this->user instanceof UserInterface;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
