<?php

namespace Framework\Validator;

abstract class AbstractValidator implements ValidatorInterface
{
    private $violations;

    final public function __construct()
    {
        $this->violations = new ViolationList();
    }

    protected function getViolations()
    {
        return $this->violations;
    }

    protected function addViolation($target, $message)
    {
        $this->violations->addViolation(new Violation($target, $message));
    }
}
