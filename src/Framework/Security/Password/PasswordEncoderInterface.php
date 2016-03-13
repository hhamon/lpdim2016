<?php

namespace Framework\Security\Password;

interface PasswordEncoderInterface
{
    public function encodePassword($plain, $salt = null);
}
