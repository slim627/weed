<?php

namespace PatientBundle\Form\Type;

use CommonBundle\Form\Type\PredefinedChoiceType;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 21.2.16
 * Time: 11.56
 */
class ComplaintStatusType extends PredefinedChoiceType
{
    const RECEIVED           = 0;
    const RESOLVED           = 1;
    const ASSIGNED           = 2;
    const PATIENT_CONTACTED  = 3;

    public static function getChoices()
    {
        return [
            self::RECEIVED          => 'Received',
            self::RESOLVED          => 'Resolved',
            self::ASSIGNED          => 'Assigned',
            self::PATIENT_CONTACTED => 'Patient contacted',
        ];
    }
}