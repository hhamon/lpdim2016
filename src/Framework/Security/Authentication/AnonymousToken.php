<?php

namespace Framework\Security\Authentication;

final class AnonymousToken implements TokenInterface
{
    private $user;

    public function __construct()
    {
        $this->user = new AnonymousUser();
    }

    public function isAuthenticated()
    {
        return false;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPermissions()
    {
        return [];
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }
}
