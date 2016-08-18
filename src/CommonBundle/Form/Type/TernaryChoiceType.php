<?php

namespace CommonBundle\Form\Type;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
class TernaryChoiceType extends BooleanChoiceType
{
    const YES_NO = 2;

    public static function getChoices()
    {
        return array_merge(parent::getChoices(), [
            self::YES_NO => 'Yes or no',
        ]);
    }
}