<?php

namespace Framework\Security;

use Framework\Security\User\UserInterface;

interface SecurityInterface
{
    /**
     * Returns whether or not the user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated();

    /**
     * Returns the current authenticated user implementation.
     *
     * @return UserInterface|null
     */
    public function getUser();

    /**
     * Returns whether or not the current authenticated user owns the permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function isGranted($permission);
}
