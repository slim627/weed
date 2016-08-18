<?php

namespace StaffBundle\Annotation;
use Doctrine\Common\Annotations\AnnotationException;


/**
 * Class KXSecure
 *
 * @Annotation
 * @Target("METHOD")
 * @package StaffBundle\Annotation
 * @author Samusevich Alexander
 */
final class KXSecureAction implements \JsonSerializable
{
    /**
     * @var string
     * @Required
     */
    private $role;

    /**
     * @var string
     */
    private $title;

    public function __construct(array $values)
    {
        if (isset($values['role'])) {
            $this->role = $values['role'];
        }
        else{
            throw new AnnotationException('Required attribute "role" not found!');
        }
        if (isset($values['title'])) {
            $this->title = $values['title'];
        }
    }

    public function jsonSerialize()
    {
        return [
            'role' => $this->getRole(),
            'title' => $this->getTitle(),
        ];
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->title;
    }
}