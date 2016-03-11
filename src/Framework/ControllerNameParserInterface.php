<?php

namespace Framework;

interface ControllerNameParserInterface
{
    /**
     * Returns the fully qualified class name for the given controller notation.
     *
     * @param string $notation The controller notation
     *
     * @return string
     */
    public function getClass($notation);
}
