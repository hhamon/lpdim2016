<?php

namespace Framework\Security\Authentication;

class BadCredentialsException extends \RuntimeException
{
    public static function missingUsernameParameter(\Exception $previous = null)
    {
        return new self('Username parameter is missing.', 0, $previous);
    }

    public static function missingPasswordParameter(\Exception $previous = null)
    {
        return new self('Password parameter is missing.', 0, $previous);
    }

    public static function invalidCredentials(\Exception $previous = null)
    {
        return new self('Presented credentials are not valid.', 0, $previous);
    }
}
