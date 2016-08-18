<?php

namespace PatientBundle\Form\Type;

use CommonBundle\Form\Type\PredefinedChoiceType;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
class ComplaintPriorityType extends PredefinedChoiceType
{
    const LOW      = 0;
    const NORMAL   = 1;
    const HIGH     = 2 ;
    const CRITICAL = 3;

    public static function getChoices()
    {
        return [
            self::LOW      => 'Low',
            self::NORMAL   => 'Normal',
            self::HIGH     => 'High',
            self::CRITICAL => 'Critical',
        ];
    }
}