<?php

namespace Framework\Validator;

interface ViolationListInterface
{
    /**
     * Returns an array of ViolationInterface instances.
     *
     * @return ViolationInterface[]
     */
    public function getViolations();

    /**
     * Adds a violation to the collection.
     *
     * @param ViolationInterface $violation
     *
     * @return void
     */
    public function addViolation(ViolationInterface $violation);
}
