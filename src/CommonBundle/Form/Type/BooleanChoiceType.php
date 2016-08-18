<?php

namespace CommonBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
class BooleanChoiceType extends PredefinedChoiceType
{
    const NO  = 0;
    const YES = 1;

    public static function getChoices()
    {
        return [
            self::NO  => 'No',
            self::YES => 'Yes',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => self::getChoices(),
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}