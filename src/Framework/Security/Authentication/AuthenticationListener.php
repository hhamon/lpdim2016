<?php

namespace Framework\Security\Authentication;

use Framework\Security\User\UserProviderInterface;

class AuthenticationListener
{
    private $authManager;
    private $userProvider;

    public function __construct(
        AuthenticationManagerInterface $authManager,
        UserProviderInterface $userProvider
    )
    {
        $this->authManager = $authManager;
        $this->userProvider = $userProvider;
    }

    public function onKernelRequest()
    {
        try {
            $token = $this->authManager->getToken();
        } catch (NoAuthenticationTokenException $e) {
            return $this->authManager->authenticate(new AnonymousToken());
        }

        if (!$token instanceof AnonymousToken && !$token->isAuthenticated()) {
            $token = new SecurityUserToken($this->userProvider->loadUserByUsername($token->getUsername()));
            $this->authManager->authenticate($token);
        }
    }
}
