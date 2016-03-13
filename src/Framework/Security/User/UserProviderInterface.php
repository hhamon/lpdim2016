<?php

namespace Framework\Security\User;

interface UserProviderInterface
{
    /**
     * Loads a UserInterface instance by its username.
     *
     * @param string $username
     *
     * @return UserInterface
     *
     * @throws UserNotFoundException
     */
    public function loadUserByUsername($username);
}
