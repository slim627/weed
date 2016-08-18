<?php

namespace CommonBundle\Form\Type;

use CommonBundle\Form\Interfaces\PredefinedChoiceTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
abstract class PredefinedChoiceType extends AbstractType implements PredefinedChoiceTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => static::getChoices(),
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}