<?php

namespace Framework\Security\Password;

interface PasswordCheckerInterface
{
    public function isPasswordValid($plain, $encoded, $salt = null);
}
