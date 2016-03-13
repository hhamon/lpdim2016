<?php

namespace Framework\Security\Authentication;

use Framework\Http\RequestInterface;

interface AuthenticatorInterface
{
    /**
     * Authenticates a request.
     *
     * @param RequestInterface $request
     * @return TokenInterface
     */
    public function authenticate(RequestInterface $request);
}
