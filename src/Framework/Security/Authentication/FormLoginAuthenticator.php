<?php

namespace Framework\Security\Authentication;

use Framework\Http\RequestInterface;
use Framework\Security\Password\PasswordCheckerInterface;
use Framework\Security\User\UserInterface;
use Framework\Security\User\UserNotFoundException;
use Framework\Security\User\UserProviderInterface;

class FormLoginAuthenticator
{
    private $authManager;
    private $passwordChecker;
    private $provider;

    public function __construct(
        UserProviderInterface $provider,
        PasswordCheckerInterface $passwordChecker,
        AuthenticationManagerInterface $authManager
    )
    {
        $this->provider = $provider;
        $this->authManager = $authManager;
        $this->passwordChecker = $passwordChecker;
    }

    public function authenticate(RequestInterface $request)
    {
        if (!$username = $request->getRequestParameter('username')) {
            throw BadCredentialsException::missingUsernameParameter();
        }

        if (!$password = $request->getRequestParameter('password')) {
            throw BadCredentialsException::missingPasswordParameter();
        }

        try {
            $user = $this->provider->loadUserByUsername($username);
        } catch (UserNotFoundException $e) {
            throw BadCredentialsException::invalidCredentials();
        }

        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException('User is not a valid UserInterface implementation.');
        }

        if (!$this->passwordChecker->isPasswordValid($password, $user->getPassword(), $user->getSalt())) {
            throw BadCredentialsException::invalidCredentials();
        }

        return $this->authManager->authenticate(new SecurityUserToken($user));
    }
}
