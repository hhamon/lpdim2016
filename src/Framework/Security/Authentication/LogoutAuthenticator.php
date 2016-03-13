<?php

namespace Framework\Security\Authentication;

use Framework\Http\RequestInterface;

class LogoutAuthenticator implements AuthenticatorInterface
{
    private $authManager;

    public function __construct(AuthenticationManagerInterface $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Authenticates a request.
     *
     * @param RequestInterface $request
     * @return TokenInterface
     */
    public function authenticate(RequestInterface $request)
    {
        $token = $this->authManager->getToken();
        if (!$token instanceof AnonymousUser) {
            $token = new AnonymousToken();
        }

        return $this->authManager->authenticate($token);
    }
}
