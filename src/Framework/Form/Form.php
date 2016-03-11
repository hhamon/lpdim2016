<?php

namespace Framework\Form;

use Framework\Http\RequestHandlerInterface;
use Framework\Http\RequestInterface;

class Form implements FormInterface, RequestHandlerInterface
{
    private $submitted;
    private $errors;
    private $name;
    private $data;

    public function __construct($name)
    {
        $this->name = $name;
        $this->submitted = false;
        $this->errors = [];
    }

    public function submit(array $data)
    {
        $this->data = $data;
        $this->submitted = true;
    }

    public function getData(callable $normalizer = null)
    {
        if (!$normalizer) {
            return $this->data;
        }

        return call_user_func_array($normalizer, [$this, $this->data]);
    }

    public function isSubmitted()
    {
        return $this->submitted;
    }

    public function isValid()
    {
        if (!$this->submitted) {
            return false;
        }

        return !$this->hasErrors();
    }

    public function getErrors($field = null)
    {
        if ($this->hasErrors($field)) {
            return $this->errors[$field];
        }

        return $this->errors;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addError(FormError $error)
    {
        $this->errors[$error->getField()][] = $error;
    }

    public function hasErrors($field = null)
    {
        if ($field && isset($this->errors[$field])) {
            return count($this->errors[$field]) > 0;
        }

        return count($this->errors) > 0;
    }

    /**
     * Returns a FormView implementation.
     *
     * @return FormView
     */
    public function createView()
    {
        return new FormView($this->data, $this->errors);
    }

    /**
     * Handles a Request object.
     *
     * @param RequestInterface $request
     *
     * @return void
     */
    public function handleRequest(RequestInterface $request)
    {
        if (!$request->isMethod(RequestInterface::POST)) {
            return;
        }

        $this->submit($request->getRequestParameter($this->getName(), []));
    }
}
