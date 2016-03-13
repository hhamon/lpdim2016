<?php

namespace Framework\Security\User;

interface UserInterface
{
    public function getUsername();
    public function getSalt();
    public function getPassword();
    public function getPermissions();
}
