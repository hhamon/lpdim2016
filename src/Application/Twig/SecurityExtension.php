<?php

namespace Application\Twig;

use Framework\Security\SecurityInterface;

class SecurityExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $security;

    public function __construct(SecurityInterface $security)
    {
        $this->security = $security;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('is_granted', [$this, 'isGranted']),
            new \Twig_SimpleFunction('is_anonymous', [$this, 'isAnonymous']),
        ];
    }

    public function getGlobals()
    {
        return [
            '_user' => $this->security->getUser(),
        ];
    }

    public function isAnonymous()
    {
        return !$this->security->isAuthenticated();
    }

    public function isGranted($permission)
    {
        return $this->security->isGranted($permission);
    }

    public function getName()
    {
        return 'security';
    }
}
