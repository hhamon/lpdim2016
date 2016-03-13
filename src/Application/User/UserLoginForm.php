<?php

namespace Application\User;

use Framework\Form\Form;
use Framework\Form\FormError;
use Framework\Http\RequestInterface;
use Framework\Validator\ValidatorInterface;

class UserLoginForm extends Form
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct('login');

        $this->validator = $validator;
    }

    public function submit(array $data)
    {
        parent::submit($data);

        $this->validate();
    }

    public function handleRequest(RequestInterface $request)
    {
        if (!$request->isMethod(RequestInterface::POST)) {
            return;
        }

        $this->submit([
            'username' => $request->getRequestParameter('username'),
            'password' => $request->getRequestParameter('password'),
        ]);
    }

    private function validate()
    {
        foreach (['username', 'password'] as $fieldName) {
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
