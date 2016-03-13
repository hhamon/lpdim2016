<?php

namespace Framework\Validator;

interface ViolationInterface
{
    public function getTarget();
    public function getMessage();
}
