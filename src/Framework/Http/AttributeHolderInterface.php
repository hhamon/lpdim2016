<?php

namespace Framework\Http;

interface AttributeHolderInterface
{
    public function setAttributes(array $attributes);
    public function setAttribute($name, $value);
    public function getAttributes();
    public function getAttribute($name, $default = null);
    public function hasAttribute($name);
}
