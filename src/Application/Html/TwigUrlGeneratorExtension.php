<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 23/02/2016
 * Time: 22:47
 */

namespace Application\Html;


use Framework\Routing\UrlGeneratorInterface;

class TwigUrlGeneratorExtension extends \Twig_Extension
{

    private $generator;

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'urlGenerator';
    }

    /**
     * TwigUrlGeneratorExtension constructor.
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    //references functions
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path',array($this,'generatePath'),['is_safe'=>['all']]),
            new \Twig_SimpleFunction('url',array($this,'generateUrl'),['is_safe'=>['all']]),
        ];
    }

    /**
     * generate a relative url
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function generatePath($name, array $params = [])
    {
        return $this->generator->generate($name,$params,UrlGeneratorInterface::RELATIVE_URL);
    }

    /**
     * generate a absolute url
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function generateUrl($name, array $params = [])
    {
        return $this->generator->generate($name,$params,UrlGeneratorInterface::ABSOLUTE_URL);
    }
}