<?php

namespace CommonBundle\Twig;

use Symfony\Component\Form\FormView;

class FormExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('form_root_name', array($this, 'getFormRootName')),
        );
    }

    public function getFormRootName(FormView $form)
    {
        $root = $form;
        while($root->parent != null){
            $root = $root->parent;
        }

        return $root->vars['name'];
    }

    public function getName()
    {
        return 'form_extension';
    }
}