<?php

namespace Application\User;

use Framework\Form\Form;
use Framework\Form\FormError;
use Framework\Validator\ValidatorInterface;

class UserSignupForm extends Form
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct('user');

        $this->validator = $validator;
    }

    public function submit(array $data)
    {
        parent::submit($data);

        $this->validate();
    }

    private function validate()
    {
        foreach (['username', 'password', 'confirmation'] as $fieldName) {
            if (!array_key_exists($fieldName, (array) $this->getData())) {
                $this->addError(new FormError($fieldName, sprintf('Missing required "%s" field name.', $fieldName)));
            }
        }

        if ($this->hasErrors()) {
            return;
        }

        $violations = $this->validator->validate($this->getData());
        foreach ($violations as $violation) {
            $this->addError(new FormError($violation->getTarget(), $violation->getMessage()));
        }
    }
}
