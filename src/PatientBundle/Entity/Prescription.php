<?php

namespace PatientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Prescription
 *
 * @ORM\Table(name="prescription")
 * @ORM\Entity(repositoryClass="PatientBundle\Entity\Repository\PrescriptionRepository")
 */
class Prescription implements \JsonSerializable
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
     * @var \DateTime
     * @ORM\Column(name="cycle_start_date", type="datetime")
     * @Assert\NotBlank()
     */
    private $cycleStartDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="cycle_end_date", type="datetime")
     * @Assert\NotBlank()
     */
    private $cycleEndDate;

    /**
     * @var string
     * @ORM\Column(name="monthly_limit", type="string")
     * @Assert\NotBlank()
     */
    private $monthlyLimit;

    /**
     * @var string
     * @ORM\Column(name="daily_limit", type="string")
     * @Assert\NotBlank()
     */
    private $dailyLimit;

    /**
     * @var integer
     * @ORM\Column(name="grams_left", type="integer", nullable=true)
     */
    private $gramsLeft;

    /**
     * @var \DateTime
     * @ORM\Column(name="prescription_start", type="datetime")
     * @Assert\NotBlank()
     */
    private $prescriptionStart;

    /**
     * @var \DateTime
     * @ORM\Column(name="prescription_end", type="datetime")
     * @Assert\NotBlank()
     */
    private $prescriptionEnd;

    /**
     * @var string
     * @ORM\Column(name="prescription_status", type="string")
     */
    private $prescriptionStatus;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="PatientBundle\Entity\Patient", inversedBy="prescriptions")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="DoctorBundle\Entity\Doctor", inversedBy="prescriptions")
     * @ORM\JoinColumn(name="doctor_id", referencedColumnName="id")
     */
    private $doctor;

    public function jsonSerialize()
    {
        return [
            'cycleStartDate'     => $this->getCycleStartDate()->getTimestamp(),
            'cycleEndDate'       => $this->getCycleEndDate()->getTimestamp(),
            'monthlyLimit'       => $this->getMonthlyLimit(),
            'dailyLimit'         => $this->getDailyLimit(),
            'prescriptionStart'  => $this->getPrescriptionStart()->getTimestamp(),
            'prescriptionFinish' => $this->getPrescriptionEnd()->getTimestamp(),
            'prescriptionStatus' => $this->getPrescriptionStatus(),
            'note'               => $this->getNote(),
            'doctor'             => $this->getDoctor(),
        ];
    }

    public function __toString()
    {
        return (string)$this->getId() ? (string)$this->getId() : 'Prescription';
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
     * Set cycleStartDate
     *
     * @param \DateTime $cycleStartDate
     *
     * @return Prescription
     */
    public function setCycleStartDate($cycleStartDate)
    {
        $this->cycleStartDate = $cycleStartDate;

        return $this;
    }

    /**
     * Get cycleStartDate
     *
     * @return \DateTime
     */
    public function getCycleStartDate()
    {
        return $this->cycleStartDate;
    }

    /**
     * Set cycleEndDate
     *
     * @param \DateTime $cycleEndDate
     *
     * @return Prescription
     */
    public function setCycleEndDate($cycleEndDate)
    {
        $this->cycleEndDate = $cycleEndDate;

        return $this;
    }

    /**
     * Get cycleEndDate
     *
     * @return \DateTime
     */
    public function getCycleEndDate()
    {
        return $this->cycleEndDate;
    }

    /**
     * Set monthlyLimit
     *
     * @param string $monthlyLimit
     *
     * @return Prescription
     */
    public function setMonthlyLimit($monthlyLimit)
    {
        $this->monthlyLimit = $monthlyLimit;

        return $this;
    }

    /**
     * Get monthlyLimit
     *
     * @return string
     */
    public function getMonthlyLimit()
    {
        return $this->monthlyLimit;
    }

    /**
     * Set dailyLimit
     *
     * @param string $dailyLimit
     *
     * @return Prescription
     */
    public function setDailyLimit($dailyLimit)
    {
        $this->dailyLimit = $dailyLimit;

        return $this;
    }

    /**
     * Get dailyLimit
     *
     * @return string
     */
    public function getDailyLimit()
    {
        return $this->dailyLimit;
    }

    /**
     * Set prescriptionStart
     *
     * @param \DateTime $prescriptionStart
     *
     * @return Prescription
     */
    public function setPrescriptionStart($prescriptionStart)
    {
        $this->prescriptionStart = $prescriptionStart;

        return $this;
    }

    /**
     * Get prescriptionStart
     *
     * @return \DateTime
     */
    public function getPrescriptionStart()
    {
        return $this->prescriptionStart;
    }

    /**
     * Set prescriptionEnd
     *
     * @param \DateTime $prescriptionEnd
     *
     * @return Prescription
     */
    public function setPrescriptionEnd($prescriptionEnd)
    {
        $this->prescriptionEnd = $prescriptionEnd;

        return $this;
    }

    /**
     * Get prescriptionEnd
     *
     * @return \DateTime
     */
    public function getPrescriptionEnd()
    {
        return $this->prescriptionEnd;
    }

    /**
     * Set prescriptionStatus
     *
     * @param string $prescriptionStatus
     *
     * @return Prescription
     */
    public function setPrescriptionStatus($prescriptionStatus)
    {
        $this->prescriptionStatus = $prescriptionStatus;

        return $this;
    }

    /**
     * Get prescriptionStatus
     *
     * @return string
     */
    public function getPrescriptionStatus()
    {
        return $this->prescriptionStatus;
    }

    /**
     * Set patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     *
     * @return Prescription
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

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Prescription
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Set doctor
     *
     * @param \DoctorBundle\Entity\Doctor $doctor
     *
     * @return Prescription
     */
    public function setDoctor(\DoctorBundle\Entity\Doctor $doctor = null)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \DoctorBundle\Entity\Doctor
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * Set gramsLeft
     *
     * @param integer $gramsLeft
     *
     * @return Prescription
     */
    public function setGramsLeft($gramsLeft)
    {
        $this->gramsLeft = $gramsLeft;

        return $this;
    }

    /**
     * Get gramsLeft
     *
     * @return integer
     */
    public function getGramsLeft()
    {
        return $this->gramsLeft;
    }
}
