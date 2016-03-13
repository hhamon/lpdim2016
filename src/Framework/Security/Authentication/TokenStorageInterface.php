<?php

namespace Framework\Security\Authentication;

interface TokenStorageInterface
{
    /**
     * Sets an authentication token or null.
     *
     * @param TokenInterface|null $token
     */
    public function setToken($token = null);

    /**
     * Returns an authentication token or null.
     *
     * @return TokenInterface|null
     */
    public function getToken();
}
