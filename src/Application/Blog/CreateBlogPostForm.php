<?php

namespace Application\Blog;

use Framework\Form\Form;
use Framework\Form\FormError;
use Framework\Validator\ValidatorInterface;
use Michelf\Markdown;

class CreateBlogPostForm extends Form
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct('blog');

        $this->validator = $validator;
    }

    public function submit(array $data)
    {
        parent::submit($data);

        $this->validate();
    }

    public function getData(callable $normalizer = null)
    {
        $normalizer = function (array $data) {
            if (empty($data['html_content'])) {
                $data['html_content'] = Markdown::defaultTransform($data['content']);
            }

            return $data;
        };

        return parent::getData($normalizer);
    }

    private function validate()
    {
        foreach (['title', 'content'] as $fieldName) {
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