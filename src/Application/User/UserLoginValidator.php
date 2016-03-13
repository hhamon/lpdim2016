<?php

namespace Application\User;

use Framework\Validator\AbstractValidator;

class UserLoginValidator extends AbstractValidator
{
    public function validate($value, array $options = [])
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Data must be an array.');
        }

        if (empty($value['username'])) {
            $this->addViolation('username', 'Le nom d\'utilisateur est obligatoire.');
        }

        if (empty($value['password'])) {
            $this->addViolation('password', 'Le mot de passe est obligatoire.');
        }

        return $this->getViolations();
    }
}
