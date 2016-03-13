<?php

namespace Framework\Form;

class FormView
{
    /** @var mixed */
    private $data;

    /** @var FormView[] */
    private $children;

    /** @var FormError[] */
    private $errors;

    public function __construct($data = null, array $errors = [])
    {
        $this->children = [];
        $this->errors = $errors;
        $this->setData($data);
    }

    public function setData($data)
    {
        $this->data = $data;
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $key => $value) {
            $errors = [];
            if (isset($this->errors[$key])) {
                $errors = $this->errors[$key];
                unset($this->errors[$key]);
            }
            $this->addChild($key, new self($value, $errors));
        }
    }

    public function __call($method, array $args = [])
    {
        try {
            return $this->get($method);
        } catch (\InvalidArgumentException $e) {
            throw new \BadMethodCallException(sprintf(
                'Method "%s" does not exist in %s class.',
                $method,
                __CLASS__
            ), 0, $e);
        }
    }

    public function get($child)
    {
        if (!array_key_exists($child, $this->children)) {
            throw new \InvalidArgumentException(sprintf('The "%s" of a FormView instance does not exist.', $child));
        }

        return $this->children[$child];
    }

    private function addChild($name, FormView $child)
    {
        $this->children[$name] = $child;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        if (count($this->errors)) {
            return true;
        }

        foreach ($this->children as $child) {
            if ($child->hasErrors()) {
                return true;
            }
        }

        return false;
    }
    public function __toString()
    {
        if (!is_scalar($this->data)) {
            throw new \RuntimeException('Unable to return a string representation of the data.');
        }

        return (string) $this->data;
    }
}
