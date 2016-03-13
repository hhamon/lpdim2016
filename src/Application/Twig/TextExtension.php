<?php

namespace Application\Twig;

use Framework\Routing\UrlGeneratorInterface;

class TextExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('richtext', function ($text) { return $text; }, [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getName()
    {
        return 'app_text';
    }
}
