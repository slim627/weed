<?php

namespace PatientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity(repositoryClass="PatientBundle\Entity\Repository\HistoryRepository")
 */
class History implements \JsonSerializable
{
    use Timestampable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="PatientBundle\Entity\Patient", inversedBy="histories")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patient;

    public function jsonSerialize()
    {
        return [
            'id'      => $this->getId(),
            'title'   => $this->getTitle(),
        ];
    }

    public function __toString()
    {
        return $this->getTitle() ? $this->getTitle() : 'History';
    }

    public function getPatientName()
    {
        return $this->getPatient()->getName();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return History
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     *
     * @return History
     */
    public function setPatient(\PatientBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \PatientBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }
}
