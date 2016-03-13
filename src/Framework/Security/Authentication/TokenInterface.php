<?php

namespace Framework\Security\Authentication;

interface TokenInterface
{
    public function isAuthenticated();
    public function getUser();
    public function getPermissions();
    public function getUsername();
}
