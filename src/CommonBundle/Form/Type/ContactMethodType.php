<?php

namespace CommonBundle\Form\Type;


/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
class ContactMethodType extends PredefinedChoiceType
{
    const PHONE = 0;
    const EMAIL = 1;
    const MAIL  = 2;

    public static function getChoices()
    {
        return [
            self::PHONE => 'Phone',
            self::EMAIL => 'Email',
            self::MAIL  => 'Mail',
        ];
    }
}