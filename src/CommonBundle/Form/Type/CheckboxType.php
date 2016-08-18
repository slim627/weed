<?php

namespace CommonBundle\Form\Type;

use CommonBundle\Form\Type\Transformer\BooleanToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType as BaseCheckboxType;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
class CheckboxType extends BaseCheckboxType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setData(isset($options['data']) ? $options['data'] : false);
        $builder->addViewTransformer(new BooleanToStringTransformer($options['value']));
    }
}