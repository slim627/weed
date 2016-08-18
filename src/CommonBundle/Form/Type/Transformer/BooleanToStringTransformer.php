<?php

namespace CommonBundle\Form\Type\Transformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer as BaseBooleanToStringTransformer;

/**
 * Created by PhpStorm.
 * User: archer
 * Date: 5.3.16
 * Time: 14.12
 */
class BooleanToStringTransformer extends BaseBooleanToStringTransformer
{
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return boolval($value);
    }
}