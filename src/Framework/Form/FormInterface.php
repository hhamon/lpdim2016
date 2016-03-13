<?php

namespace Framework\Form;

interface FormInterface
{
    public function submit(array $data);
    public function getData(callable $normalizer = null);
    public function isSubmitted();
    public function isValid();
    public function getName();
    public function getErrors($field = null);
    public function addError(FormError $error);
    public function hasErrors($field = null);

    /**
     * Returns a FormView implementation.
     *
     * @return FormView
     */
    public function createView();
}
