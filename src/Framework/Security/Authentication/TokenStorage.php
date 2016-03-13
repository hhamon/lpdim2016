<?php

namespace Framework\Security\Authentication;

final class TokenStorage implements TokenStorageInterface
{
    private $token;

    /**
     * Sets an authentication token or null.
     *
     * @param TokenInterface|null $token
     */
    public function setToken($token = null)
    {
        $this->token = $token;
    }

    /**
     * Returns an authentication token or null.
     *
     * @return TokenInterface|null
     */
    public function getToken()
    {
        return $this->token;
    }
}
