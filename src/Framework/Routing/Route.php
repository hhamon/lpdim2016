<?php

namespace Framework\Routing;

class Route
{
    private $path;
    private $parameters;
    private $methods;
    private $requirements;

    public function __construct($path, array $parameters = [], array $methods = [], $requirements = [])
    {
        $this->path = $path;
        $this->parameters = $parameters;
        $this->methods = $methods;
        $this->requirements = $requirements;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

    public function getMethods()
    {
        $methods = $this->methods;

        if (in_array('GET', $methods) && !in_array('HEAD', $methods)) {
            $methods[] = 'HEAD';
        }

        return $methods;
    }

   private function getPattern()
   {
       $pattern = '#^'.preg_quote($this->path).'$#';
       if (!preg_match_all('#\{([a-z]+)\}#i', $this->path, $matches, PREG_OFFSET_CAPTURE|PREG_SET_ORDER)) {
           return $pattern;
       }

       $tokens = [];
       foreach ($matches as $match) {
           $parameter = $match[1][0];
           $token = '\\{'.$parameter.'\\}';
           $tokens[$token] = $this->getRequirementPattern($parameter);
       }

       return str_replace(array_keys($tokens), array_values($tokens), $pattern);
   }

    private function getRequirementPattern($parameter)
    {
        $requirement = $this->getRequirement($parameter);

        return sprintf('(?P<%s>%s)', $parameter, ($requirement ?: '.+'));
    }

    public function getRequirement($name)
    {
        return isset($this->requirements[$name]) ? $this->requirements[$name] : null;
    }

    private function executeRegexAgainst($path)
    {
        if (!preg_match($this->getPattern(), $path, $matches)) {
            throw new \RuntimeException('Route does not match pattern.');
        }

        $parameters = array_merge($this->parameters, $matches);
        $this->parameters = [];
        foreach ($parameters as $key => $value) {
            if (!is_string($key)) {
                continue;
            }
            $this->parameters[$key] = $value;
        }
        
        return $matches;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function match($path)
    {
        try {
            $this->executeRegexAgainst($path);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getPath()
    {
        return $this->path;
    }
}
