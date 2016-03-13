<?php

namespace Application\Blog;

use Framework\Validator\AbstractValidator;

class BlogPostValidator extends AbstractValidator
{
    public function validate($value, array $options = [])
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Data must be an array.');
        }

        if (empty($value['title'])) {
            $this->addViolation('title', 'Le titre est obligatoire.');
        }

        if (!empty($value['title']) && mb_strlen($value['title']) < 5) {
            $this->addViolation('title', 'Le titre est trop court ! Il doit contenir au moins 5 caractères.');
        }

        if (!empty($value['title']) && mb_strlen($value['title']) > 100) {
            $this->addViolation('title', 'Le titre est trop long ! Il ne doit pas dépasser 100 caractères.');
        }

        if (empty($value['content'])) {
            $this->addViolation('content', 'Le contenu de l\'article est obligatoire.');
        }

        return $this->getViolations();
    }
}
