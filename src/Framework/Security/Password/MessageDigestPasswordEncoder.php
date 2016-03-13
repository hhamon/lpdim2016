<?php

namespace Framework\Security\Password;

class MessageDigestPasswordEncoder implements PasswordEncoderInterface, PasswordCheckerInterface
{
    private $algorithm;
    private $encodeAsBase64;

    public function __construct($algorithm, $encodeAsBase64  = true)
    {
        if (!in_array($algorithm, hash_algos())) {
            throw new \InvalidArgumentException(sprintf('The %s algorithm is not supported. It must be one of %s.', $algorithm, implode(', ', hash_algos())));
        }

        $this->algorithm = $algorithm;
        $this->encodeAsBase64 = (bool) $encodeAsBase64;
    }

    public function isPasswordValid($plain, $encoded, $salt = null)
    {
        return $this->encodePassword($plain, $salt) === $encoded;
    }

    public function encodePassword($plain, $salt = null)
    {
        $password = sprintf('${%s}{%s}', $plain, $salt);
        $password = hash($this->algorithm, $password);

        if ($this->encodeAsBase64) {
            $password = base64_encode($password);
        }

        return $password;
    }
}
