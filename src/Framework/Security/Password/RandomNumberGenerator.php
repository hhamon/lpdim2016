<?php

namespace Framework\Security\Password;

class RandomNumberGenerator
{
    public static function randomBytes($length = 128)
    {
        return (new self())->generateRandomBytes($length);
    }

    public function generateRandomBytes($length = 128)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#%?&*()-_+=~^`/\!|';
        $charactersLength = mb_strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $characters = str_shuffle($characters);
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
