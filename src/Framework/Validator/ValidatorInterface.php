<?php

namespace Framework\Validator;

interface ValidatorInterface
{
    /**
     * Validates a value.
     *
     * @param mixed $value   The value to validate (int, string, array, object, etc.)
     * @param array $options A list of options to pass to the validator
     *
     * @return ViolationList
     */
    public function validate($value, array $options = []);
}
