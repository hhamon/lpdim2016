<?php

namespace Framework\Validator;

class ViolationList implements ViolationListInterface, \Countable, \Iterator
{
    private $violations;

    public function __construct()
    {
        $this->violations = [];
    }

    /**
     * Returns an array of ViolationInterface instances.
     *
     * @return ViolationInterface[]
     */
    public function getViolations()
    {
        return $this->violations;
    }

    /**
     * Adds a violation to the collection.
     *
     * @param ViolationInterface $violation
     *
     * @return void
     */
    public function addViolation(ViolationInterface $violation)
    {
        if (!in_array($violation, $this->violations)) {
            $this->violations[] = $violation;
        }
    }

    public function count()
    {
        return count($this->violations);
    }

    public function current()
    {
        return current($this->violations);
    }

    public function next()
    {
        next($this->violations);
    }

    public function key()
    {
        return key($this->violations);
    }

    public function valid()
    {
        return null !== $this->current() && null !== $this->key();
    }

    public function rewind()
    {
        reset($this->violations);
    }
}
