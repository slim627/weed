<?php

namespace StaffBundle\Annotation;


/**
 * Class KXSecureClass
 *
 * @Annotation
 * @Target("CLASS")
 * @package StaffBundle\Annotation
 * @author Samusevich Alexander
 */
final class KXSecureClass
{
    /**
     * @var string
     */
    private $title;

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->title = $values['value'];
        }
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->title;
    }
}