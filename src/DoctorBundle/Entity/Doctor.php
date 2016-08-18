<?php

namespace DoctorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Doctor
 *
 * @ORM\Table(name="doctor")
 * @ORM\Entity()
 */
class Doctor implements \JsonSerializable
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
     * @var string $first_name
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string $last_name
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @var string $licenseNumber
     *
     * @ORM\Column(name="license_number", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $licenseNumber;

    /**
     * @var boolean
     * @ORM\Column(name="license_number_verified", type="boolean")
     */
    private $licenseNumberVerified = false;

    /**
     * @var string $officeAddress
     *
     * @ORM\Column(name="office_address", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $officeAddress;

    /**
     * @var string $primaryCenter
     *
     * @ORM\Column(name="primary_center", type="string", length=255, nullable=true)
     */
    private $primaryCenter;

    /**
     * @var \DateTime
     * @ORM\Column(name="designation_date", type="date")
     * @Assert\NotBlank()
     */
    private $designationDate;

    /**
     * @var string $recommendationsNotes
     *
     * @ORM\Column(name="recommendations_notes", type="text", nullable=true)
     */
    private $recommendationsNotes;

    /**
     * @var boolean
     * @ORM\Column(name="recommendations_verified", type="boolean", options={"default" = 0})
     */
    private $recommendationsVerified = false;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\Patient", mappedBy="doctor", cascade={"persist"})
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\Prescription", mappedBy="doctor", cascade={"persist"})
     */
    private $prescriptions;

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'phone' => $this->getPhone(),
            'license_number' => $this->getLicenseNumber(),
            'license_number_verified' => $this->getLicenseNumberVerified(),
            'office_address' => $this->getOfficeAddress(),
            'primary_center' => $this->getPrimaryCenter(),
            'designation_date' => ($this->getDesignationDate()) ? $this->getDesignationDate()->getTimestamp() : null,
            'recommendations_notes' => $this->getRecommendationsNotes(),
            'recommendations_verified' => $this->getRecommendationsVerified(),
        ];
    }

    public function __toString()
    {
        return $this->getName() ? $this->getName() : 'New doctor';
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->patients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prescriptions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Doctor
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Doctor
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Doctor
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set licenseNumber
     *
     * @param string $licenseNumber
     *
     * @return Doctor
     */
    public function setLicenseNumber($licenseNumber)
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    /**
     * Get licenseNumber
     *
     * @return string
     */
    public function getLicenseNumber()
    {
        return $this->licenseNumber;
    }

    /**
     * Set licenseNumberVerified
     *
     * @param boolean $licenseNumberVerified
     *
     * @return Doctor
     */
    public function setLicenseNumberVerified($licenseNumberVerified)
    {
        $this->licenseNumberVerified = $licenseNumberVerified;

        return $this;
    }

    /**
     * Get licenseNumberVerified
     *
     * @return boolean
     */
    public function getLicenseNumberVerified()
    {
        return $this->licenseNumberVerified;
    }

    /**
     * Set officeAddress
     *
     * @param string $officeAddress
     *
     * @return Doctor
     */
    public function setOfficeAddress($officeAddress)
    {
        $this->officeAddress = $officeAddress;

        return $this;
    }

    /**
     * Get officeAddress
     *
     * @return string
     */
    public function getOfficeAddress()
    {
        return $this->officeAddress;
    }

    /**
     * Set primaryCenter
     *
     * @param string $primaryCenter
     *
     * @return Doctor
     */
    public function setPrimaryCenter($primaryCenter)
    {
        $this->primaryCenter = $primaryCenter;

        return $this;
    }

    /**
     * Get primaryCenter
     *
     * @return string
     */
    public function getPrimaryCenter()
    {
        return $this->primaryCenter;
    }

    /**
     * Set designationDate
     *
     * @param \DateTime $designationDate
     *
     * @return Doctor
     */
    public function setDesignationDate($designationDate)
    {
        $this->designationDate = $designationDate;

        return $this;
    }

    /**
     * Get designationDate
     *
     * @return \DateTime
     */
    public function getDesignationDate()
    {
        return $this->designationDate;
    }

    /**
     * Set recommendationsNotes
     *
     * @param string $recommendationsNotes
     *
     * @return Doctor
     */
    public function setRecommendationsNotes($recommendationsNotes)
    {
        $this->recommendationsNotes = $recommendationsNotes;

        return $this;
    }

    /**
     * Get recommendationsNotes
     *
     * @return string
     */
    public function getRecommendationsNotes()
    {
        return $this->recommendationsNotes;
    }

    /**
     * Set recommendationsVerified
     *
     * @param boolean $recommendationsVerified
     *
     * @return Doctor
     */
    public function setRecommendationsVerified($recommendationsVerified)
    {
        $this->recommendationsVerified = $recommendationsVerified;

        return $this;
    }

    /**
     * Get recommendationsVerified
     *
     * @return boolean
     */
    public function getRecommendationsVerified()
    {
        return $this->recommendationsVerified;
    }

    /**
     * Add patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     *
     * @return Doctor
     */
    public function addPatient(\PatientBundle\Entity\Patient $patient)
    {
        $this->patients[] = $patient;

        return $this;
    }

    /**
     * Remove patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     */
    public function removePatient(\PatientBundle\Entity\Patient $patient)
    {
        $this->patients->removeElement($patient);
    }

    /**
     * Get patients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatients()
    {
        return $this->patients;
    }

    /**
     * Add prescription
     *
     * @param \PatientBundle\Entity\Prescription $prescription
     *
     * @return Doctor
     */
    public function addPrescription(\PatientBundle\Entity\Prescription $prescription)
    {
        $this->prescriptions[] = $prescription;

        return $this;
    }

    /**
     * Remove prescription
     *
     * @param \PatientBundle\Entity\Prescription $prescription
     */
    public function removePrescription(\PatientBundle\Entity\Prescription $prescription)
    {
        $this->prescriptions->removeElement($prescription);
    }

    /**
     * Get prescriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrescriptions()
    {
        return $this->prescriptions;
    }
}
