<?php
/**
 * Created by PhpStorm.
 * User: archer
 * Date: 16.2.16
 * Time: 14.55
 */

namespace StaffBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccessLevel
 *
 * @ORM\Table(name="access_level")
 * @ORM\Entity()
 */
class AccessLevel implements \JsonSerializable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $role
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AccessLevel")
     * @ORM\JoinColumn(name="parent_id", nullable=true, referencedColumnName="id")
     **/
    private $parent;

    public function __toString()
    {
        return $this->getName();
    }

    public function jsonSerialize()
    {
        return [
            'role' => $this->getRole(),
            'name' => $this->getName(),
        ];
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return AccessLevel
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AccessLevel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parent
     *
     * @param \StaffBundle\Entity\AccessLevel $parent
     *
     * @return AccessLevel
     */
    public function setParent(\StaffBundle\Entity\AccessLevel $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \StaffBundle\Entity\AccessLevel
     */
    public function getParent()
    {
        return $this->parent;
    }
}
