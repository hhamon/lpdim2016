<?php

namespace Framework\Routing;


use Framework\Routing\Loader\FileLoaderInterface;

class UrlGenerator implements UrlGeneratorInterface
{

    /**
     * @var RouterInterface
     */
    private $configuration;
    private $loader;
    private $routes;
    private $test;
    private $domain;

    /**
     * UrlGenerator constructor.
     * @param $configuration
     * @param FileLoaderInterface $loader
     */
    public function __construct($configuration,FileLoaderInterface $loader)
    {
        $this->configuration = $configuration;
        $this->loader = $loader;
    }

    /**
     * Generates a relative or absolute URL based on routing configuration.
     *
     * @param string $name The route name
     * @param array $params The route parameters to replace tokens
     * @param int $type Whether or not to generate an absolute URL
     * @return mixed|string
     */
    public function generate($name, array $params = [], $type = self::RELATIVE_URL)
    {
        if (null === $this->routes) {
            $this->routes = $this->loader->load($this->configuration);
        }
        foreach ($this->routes as $key => $route) {
            if($name == $key){
                return $this->generateRoute($route,$params,$type);
            }
        }
        throw new \InvalidArgumentException(
            sprintf('Route for name %s does not match',$name)
        );
    }

    private function generateRoute(Route $route,array $params, $type)
    {
        $url = '/index.php'.$route->getPath();
        $requirements = $route->getRequirements();
        $page = false;
        preg_match_all('#\{([a-z]+)\}#i', $url, $matches, PREG_OFFSET_CAPTURE|PREG_SET_ORDER);
        if($matches){
            foreach ($matches as $match) {
                if(!in_array($match[1][0],array_keys($params))){
                    throw new \InvalidArgumentException(sprintf('Missing Param %s.',$match[1][0]));
                }
            }
        }
        if(isset($params['page']) && preg_match('#[0-9]+#',$params['page'])){
            $page = $params['page'];
            unset($params['page']);
        }
        foreach ($params as $key => $param) {
            if(!preg_match('#'.$requirements[$key].'#',$param)){
                throw new \InvalidArgumentException(
                    sprintf('param for key %s does not match pattern',$key)
                );
            }
            $url = str_replace('{'.$key.'}',$param,$url);
        }
        if($page){
            $url .= "?page=".$page;
        }
        if($type == self::ABSOLUTE_URL && !$this->test){
            $scriptName = str_replace('/index.php','',$_SERVER['SCRIPT_NAME']);
            $host = 'http://'.$_SERVER['HTTP_HOST'];
            return $host.$scriptName.$url;
        }
        if($type == self::ABSOLUTE_URL && $this->test && $this->domain){
            $host = 'http://'.$this->domain;
            return $host.$url;
        }
        if($type == self::RELATIVE_URL){
            return $url;
        }
    }

    /**
     * @param $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }
}
