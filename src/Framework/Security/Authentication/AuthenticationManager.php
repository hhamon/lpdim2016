<?php

namespace Framework\Security\Authentication;

use Framework\Session\SessionInterface;

class AuthenticationManager implements AuthenticationManagerInterface
{
    const STORAGE_KEY = 'security.token';

    private $tokenStorage;
    private $sessionStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionInterface $sessionStorage
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->sessionStorage = $sessionStorage;
    }

    public function authenticate(TokenInterface $token)
    {
        $this->tokenStorage->setToken($token);
        $this->sessionStorage->store(self::STORAGE_KEY, $token);

        return $token;
    }

    public function getToken()
    {
        if ($token = $this->sessionStorage->fetch(self::STORAGE_KEY)) {
            return $token;
        }

        if ($token = $this->tokenStorage->getToken()) {
            return $token;
        }

        throw new NoAuthenticationTokenException();
    }
}
