<?php

namespace PatientBundle\Form\Type;

use CommonBundle\Form\Type\PredefinedChoiceType;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
class ComplaintSourceType extends PredefinedChoiceType
{
    const EMAIL   = 0;
    const PHONE   = 1;
    const FAX     = 2;
    const LETTER  = 3;
    const WEBSITE = 4;

    public static function getChoices()
    {
        return [
            self::EMAIL   => 'Email',
            self::PHONE   => 'Phone',
            self::FAX     => 'Fax',
            self::LETTER  => 'Letter',
            self::WEBSITE => 'Website',
        ];
    }
}