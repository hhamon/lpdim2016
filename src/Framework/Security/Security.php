<?php

namespace Framework\Security;

use Framework\Security\Authentication\TokenStorageInterface;
use Framework\Security\User\UserInterface;

class Security implements SecurityInterface
{
    private $tokenStorage;

    /**
     * Constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns whether or not the user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        if (!$token = $this->getToken()) {
            return false;
        }

        return $token->isAuthenticated();
    }

    /**
     * Returns the current authenticated user implementation.
     *
     * @return UserInterface|null
     */
    public function getUser()
    {
        if ($token = $this->getToken()) {
            return $token->getUser();
        }
    }

    /**
     * Returns whether or not the current authenticated user owns the permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function isGranted($permission)
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        return in_array($permission, $this->getToken()->getPermissions());
    }

    private function getToken()
    {
        return $this->tokenStorage->getToken();
    }
}
