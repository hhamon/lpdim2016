<?php

namespace Framework;

class DefaultControllerNameParser implements ControllerNameParserInterface
{
    /**
     * Returns the fully qualified class name for the given controller notation.
     *
     * @param string $notation The controller notation
     *
     * @return string
     */
    public function getClass($notation)
    {
        if (!class_exists($notation)) {
            throw new \RuntimeException(sprintf('Controller class "%s" does not exist or cannot be autoloaded.', $notation));
        }

        return $notation;
    }
}
