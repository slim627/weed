<?php
/**
 * Created by PhpStorm.
 * User: archer
 * Date: 16.2.16
 * Time: 14.55
 */

namespace StaffBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity()
 */
class Notification implements \JsonSerializable
{
    use Timestampable;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="notifications")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     **/
    private $employee;

    /**
     * @var boolean
     * @ORM\Column(name="is_viewed", type="boolean", options={"defaultValue": false})
     */
    private $isViewed = false;

    public function __toString()
    {
        return $this->getText();
    }

    public function jsonSerialize()
    {
        return [
            'created_at' => $this->getCreatedAt()->getTimestamp(),
            'text' => $this->getText(),
            'is_viewed' => $this->getIsViewed(),
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
     * Set text
     *
     * @param string $text
     *
     * @return Notification
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set employee
     *
     * @param \StaffBundle\Entity\Employee $employee
     *
     * @return Notification
     */
    public function setEmployee(\StaffBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \StaffBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set isViewed
     *
     * @param boolean $isViewed
     *
     * @return Notification
     */
    public function setIsViewed($isViewed)
    {
        $this->isViewed = $isViewed;

        return $this;
    }

    /**
     * Get isViewed
     *
     * @return boolean
     */
    public function getIsViewed()
    {
        return $this->isViewed;
    }
}
