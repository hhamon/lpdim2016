<?php

namespace Framework\Form;

class FormError implements FormErrorInterface
{
    private $field;
    private $message;

    public function __construct($field, $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function __toString()
    {
        return (string) $this->message;
    }
}
