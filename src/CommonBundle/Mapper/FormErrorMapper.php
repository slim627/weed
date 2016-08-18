<?php

namespace CommonBundle\Mapper;

use Symfony\Component\Form\Form;

class FormErrorMapper implements \JsonSerializable
{
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function transform()
    {
        $errors = null;
        foreach ($this->form as $fieldName => $field) {
            foreach($field->getErrors(true) as $error){
                $errors[$fieldName][] = $error->getMessage();
            }
        }

        return $errors;
    }

    public function jsonSerialize()
    {
        return $this->transform();
    }
}