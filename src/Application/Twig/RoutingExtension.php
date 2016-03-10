<?php

namespace Application\Twig;

use Framework\Routing\UrlGeneratorInterface;

class RoutingExtension extends \Twig_Extension
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path', [$this, 'getPath']),
            new \Twig_SimpleFunction('url', [$this, 'getUrl']),
        ];
    }

    public function getPath($route, array $params = [])
    {
        return $this->urlGenerator->generate($route, $params, UrlGeneratorInterface::RELATIVE_URL);
    }

    public function getUrl($route, array $params = [])
    {
        return $this->urlGenerator->generate($route, $params, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function getName()
    {
        return 'routing';
    }
}
