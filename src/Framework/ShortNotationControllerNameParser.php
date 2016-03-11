<?php

namespace Framework;

class ShortNotationControllerNameParser implements ControllerNameParserInterface
{
    private $fallback;
    private $mappings;

    /**
     * Constructor.
     *
     * @param ControllerNameParserInterface $fallback The fallback controller name parser
     * @param array                         $mappings The mapping between aliases and namespaces
     */
    public function __construct(ControllerNameParserInterface $fallback, array $mappings)
    {
        $this->fallback = $fallback;
        $this->mappings = $mappings;
    }

    /**
     * Returns the fully qualified class name for the given controller notation.
     *
     * @param string $notation The controller notation
     *
     * @return string
     */
    public function getClass($notation)
    {
        if ($this->isShortNotation($notation)) {
            $notation = $this->findControllerClass($notation);
        }

        return $this->fallback->getClass($notation);
    }

    /**
     * Returns whether or not the notation is a short controller notation.
     *
     * @param string $notation The controller notation
     *
     * @return bool
     */
    private function isShortNotation($notation)
    {
        return stripos($notation, ':') > 0;
    }

    /**
     * Finds the matching controller class from the short notation.
     *
     * @param string $notation The controller notation
     *
     * @return string
     */
    private function findControllerClass($notation)
    {
        $parts = explode(':', $notation);
        $alias = current($parts);
        if (!array_key_exists($alias, $this->mappings)) {
            throw new \RuntimeException(sprintf('No matching namespace registered for alias controller path "%s".', $notation));
        }

        array_shift($parts);

        return sprintf('%s\\%sAction', $this->mappings[$alias], implode('\\', $parts));
    }
}
