<?php

namespace Framework\Security\Authentication;

interface AuthenticationManagerInterface
{
    /**
     * Authenticates a security token.
     *
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function authenticate(TokenInterface $token);

    /**
     * Returns the current authentication token.
     *
     * @return TokenInterface
     * @throws NoAuthenticationTokenException
     */
    public function getToken();
}
