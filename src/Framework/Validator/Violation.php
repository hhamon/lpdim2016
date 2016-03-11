<?php

namespace Framework\Validator;

class Violation implements ViolationInterface
{
    private $target;
    private $message;

    public function __construct($target, $message)
    {
        $this->target = $target;
        $this->message = $message;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
