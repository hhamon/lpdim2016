<?php

namespace Application\User;

use Framework\Validator\AbstractValidator;

class UserSignupValidator extends AbstractValidator
{
    public function validate($value, array $options = [])
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Data must be an array.');
        }

        if (empty($value['username'])) {
            $this->addViolation('username', 'Le nom d\'utilisateur est obligatoire.');
        }

        if (!empty($value['username']) && mb_strlen($value['username']) < 5) {
            $this->addViolation('username', 'Le nom d\'utilisateur est trop court ! Il doit contenir au moins 5 caractères.');
        }

        if (!empty($value['username']) && mb_strlen($value['username']) > 25) {
            $this->addViolation('username', 'Le nom d\'utilisateur est trop long ! Il ne doit pas dépasser 25 caractères.');
        }

        if (empty($value['password'])) {
            $this->addViolation('password', 'Le mot de passe est obligatoire.');
        }

        if (!empty($value['password']) && mb_strlen($value['password']) < 8) {
            $this->addViolation('password', 'Le mot de passe est trop court ! Il doit contenir au moins 8 caractères.');
        }

        if (empty($value['confirmation'])) {
            $this->addViolation('confirmation', 'La confirmation du mot de passe est obligatoire.');
        }

        if ($value['confirmation'] !== $value['password']) {
            $this->addViolation('password', 'Le mot de passe et sa confirmation doivent correspondre.');
        }

        return $this->getViolations();
    }
}
