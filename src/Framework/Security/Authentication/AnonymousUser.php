<?php

namespace Framework\Security\Authentication;

use Framework\Security\User\UserInterface;

final class AnonymousUser implements UserInterface
{
    public function getUsername()
    {
        return 'anonymous';
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public function getSalt()
    {
        return '';
    }

    public function getPassword()
    {
        return '';
    }

    public function getPermissions()
    {
        return [];
    }
}
