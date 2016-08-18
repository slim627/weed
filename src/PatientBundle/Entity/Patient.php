<?php

namespace PatientBundle\Entity;

use CommonBundle\Form\Type\ContactMethodType;
use DoctorBundle\Entity\Doctor;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Patient
 *
 * @ORM\Table(name="patient")
 * @ORM\Entity(repositoryClass="PatientBundle\Entity\Repository\PatientRepository")
 */
class Patient implements \JsonSerializable
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
     * @ORM\Column(name="id_number", type="string", nullable=true)
     */
    private $idNumber;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_of_birth", type="datetime")
     * @Assert\NotBlank()
     */
    private $dateOfBirth;

    /**
     * @var int
     * @ORM\Column(name="last_order", type="integer", nullable=true)
     */
    private $lastOrder;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string")
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(name="mailing_address", type="string")
     * @Assert\NotBlank()
     */
    private $mailingAddress;

    /**
     * @var string
     * @ORM\Column(name="delivery_address", type="string")
     * @Assert\NotBlank()
     */
    private $deliveryAddress;

    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="preferred_contact_method", type="integer")
     */
    private $preferredContactMethod = ContactMethodType::PHONE;

    /**
     * @var string
     * @ORM\Column(name="tax_exemption", type="string", nullable=true)
     */
    private $taxExemption;

    /**
     * @var string
     * @ORM\Column(name="health_number", type="string")
     * @Assert\NotBlank()
     */
    private $healthNumber;

    /**
     * @var boolean
     * @ORM\Column(name="verified", type="boolean", options={"default" = 0})
     */
    private $verified = 0;

    /**
     * @var string
     * @ORM\Column(name="diagnosis", type="string")
     * @Assert\NotBlank()
     */
    private $diagnosis;

    /**
     * @var boolean
     * @ORM\Column(name="online_account", type="boolean", options={"default" = 0})
     */
    private $onlineAccount = 0;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_visit", type="datetime", nullable=true)
     */
    private $lastVisit;

    /**
     * @var \DateTime
     * @ORM\Column(name="member_since", type="datetime", nullable=true)
     */
    private $memberSince;

    /**
     * @var \DateTime
     * @ORM\Column(name="member_expire", type="datetime", nullable=true)
     */
    private $memberExpire;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\NoteAndTask", mappedBy="patient", cascade={"all"}, orphanRemoval=true)
     */
    private $notesAndTasks;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\Complaint", mappedBy="patient", cascade={"all"}, orphanRemoval=true)
     */
    private $complains;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\History", mappedBy="patient", cascade={"all"}, orphanRemoval=true)
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\Prescription", mappedBy="patient", cascade={"all"}, orphanRemoval=true)
     */
    private $prescriptions;

    /**
     * @ORM\ManyToOne(targetEntity="DoctorBundle\Entity\Doctor", inversedBy="patients")
     * @ORM\JoinColumn(name="doctor_id", referencedColumnName="id")
     */
    private $doctor;

    /**
     * @ORM\ManyToOne(targetEntity="StaffBundle\Entity\Employee", inversedBy="patients")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id", nullable=true)
     */
    private $accountManager;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\File", mappedBy="patient", cascade={"all"})
     */
    private $files;

    public function jsonSerialize()
    {
        return [
            'id'                     => $this->getId(),
            'idNumber'               => $this->getIdNumber(),
            'name'                   => $this->getName(),
            'dateOfBirth'            => $this->getDateOfBirth()->getTimestamp(),
            'prescExpiry'            => $this->getPrescExpiry(),
            'monthlyLimit'           => $this->getMonthlyLimit(),
            'dailyLimit'             => $this->getDailyLimit(),
            'cycleStart'             => $this->getCycleStart(),
            'cycleEnd'               => $this->getCycleEnd(),
            'gramsLeft'              => $this->getGramsLeft(),
            'lastOrder'              => $this->getLastOrder(),
            'healthNumber'           => $this->getHealthNumber(),
            'verified'               => $this->getVerified(),
            'createdAt'              => $this->getCreatedAt()->getTimestamp(),
            'phone'                  => $this->getPhone(),
            'onlineAccount'          => $this->getOnlineAccount(),
            'lastOnlineVisit'        => $this->getLastVisit() ? $this->getLastVisit()->getTimestamp() : null,
            'mailingAddress'         => $this->getMailingAddress(),
            'deliveryAddress'        => $this->getDeliveryAddress(),
            'email'                  => $this->getEmail(),
            'preferredContactMethod' => ContactMethodType::getChoices()[$this->getPreferredContactMethod()],
            'taxExemption'           => $this->getTaxExemption(),
            'diagnosis'              => $this->getDiagnosis(),
            'accountManager'         => $this->getAccountManager(),
            'memberSince'            => ($this->getMemberSince()) ? $this->getMemberSince()->getTimestamp() : null,
            'memberExpire'           => ($this->getMemberExpire()) ? $this->getMemberExpire()->getTimestamp() : null,
            'doctor'                 => $this->getDoctor(),
            'lastPrescription'       => $this->getPrescriptions() ? $this->getPrescriptions()->last() : null,
            'uploadedFiles'         => $this->getFiles()->toArray(),
        ];
    }

    public function __toString()
    {
        return $this->getName() ? $this->getName() : 'New patient';
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
     * Set name
     *
     * @param string $name
     *
     * @return Patient
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
     * Get prescExpiry
     *
     * @return \DateTime
     */
    public function getPrescExpiry()
    {
        if(!$this->getPrescriptions()->count()){
            return null;
        }

        $prescription = $this->getPrescriptions()->last();
        $prescExpiry = $prescription->getPrescriptionEnd()->getTimestamp();

        return $prescExpiry;
    }

    /**
     * Get monthlyLimit
     *
     * @return string
     */
    public function getMonthlyLimit()
    {
        if(!$this->getPrescriptions()->count()){
            return null;
        }

        $prescription = $this->getPrescriptions()->last();
        $monthlyLimit = $prescription->getMonthlyLimit();

        return $monthlyLimit;
    }

    /**
     * Get dailyLimit
     *
     * @return string
     */
    public function getDailyLimit()
    {
        if(!$this->getPrescriptions()->count()){
            return null;
        }

        $prescription = $this->getPrescriptions()->last();
        $dailyLimit = $prescription->getDailyLimit();

        return $dailyLimit;
    }

    /**
     * Get cycleStart
     *
     * @return \DateTime
     */
    public function getCycleStart()
    {
        if(!$this->getPrescriptions()->count()){
            return null;
        }

        $prescription = $this->getPrescriptions()->last();
        $cycleStart = $prescription->getCycleStartDate()->getTimestamp();

        return $cycleStart;
    }

    /**
     * Get cycleEnd
     *
     * @return \DateTime
     */
    public function getCycleEnd()
    {
        if(!$this->getPrescriptions()->count()){
            return null;
        }

        $prescription = $this->getPrescriptions()->last();
        $cycleEnd = $prescription->getCycleEndDate()->getTimestamp();

        return $cycleEnd;
    }

    /**
     * Get gramsLeft
     *
     * @return integer
     */
    public function getGramsLeft()
    {
        if(!$this->getPrescriptions()->count()){
            return null;
        }

        $prescription = $this->getPrescriptions()->last();
        $gramsLeft = $prescription->getGramsLeft();

        return $gramsLeft;
    }

    /**
     * Set lastOrder
     *
     * @param integer $lastOrder
     *
     * @return Patient
     */
    public function setLastOrder($lastOrder)
    {
        $this->lastOrder = $lastOrder;

        return $this;
    }

    /**
     * Get lastOrder
     *
     * @return integer
     */
    public function getLastOrder()
    {
        return $this->lastOrder;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Patient
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
     * Set healthNumber
     *
     * @param string $healthNumber
     *
     * @return Patient
     */
    public function setHealthNumber($healthNumber)
    {
        $this->healthNumber = $healthNumber;

        return $this;
    }

    /**
     * Get healthNumber
     *
     * @return string
     */
    public function getHealthNumber()
    {
        return $this->healthNumber;
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     *
     * @return Patient
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get verified
     *
     * @return boolean
     */
    public function getVerified()
    {
        return $this->verified;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notesAndTasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prescriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->complains     = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notesAndTask
     *
     * @param \PatientBundle\Entity\NoteAndTask $notesAndTask
     *
     * @return Patient
     */
    public function addNoteAndTask(\PatientBundle\Entity\NoteAndTask $notesAndTask)
    {
        $this->notesAndTasks[] = $notesAndTask;

        return $this;
    }

    /**
     * Remove notesAndTask
     *
     * @param \PatientBundle\Entity\NoteAndTask $notesAndTask
     */
    public function removeNotesAndTask(\PatientBundle\Entity\NoteAndTask $notesAndTask)
    {
        $this->notesAndTasks->removeElement($notesAndTask);
    }

    /**
     * Get notesAndTasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotesAndTasks()
    {
        return $this->notesAndTasks;
    }

    /**
     * Set idNumber
     *
     * @param string $idNumber
     *
     * @return Patient
     */
    public function setIdNumber($idNumber)
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    /**
     * Get idNumber
     *
     * @return string
     */
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    /**
     * Add complain
     *
     * @param \PatientBundle\Entity\Complaint $complain
     *
     * @return Patient
     */
    public function addComplain(\PatientBundle\Entity\Complaint $complain)
    {
        $this->complains[] = $complain;

        return $this;
    }

    /**
     * Remove complain
     *
     * @param \PatientBundle\Entity\Complaint $complain
     */
    public function removeComplain(\PatientBundle\Entity\Complaint $complain)
    {
        $this->complains->removeElement($complain);
    }

    /**
     * Get complains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComplains()
    {
        return $this->complains;
    }

    /**
     * Add history
     *
     * @param \PatientBundle\Entity\History $history
     *
     * @return Patient
     */
    public function addHistory(\PatientBundle\Entity\History $history)
    {
        $this->histories[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \PatientBundle\Entity\History $history
     */
    public function removeHistory(\PatientBundle\Entity\History $history)
    {
        $this->histories->removeElement($history);
    }

    /**
     * Get histories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistories()
    {
        return $this->histories;
    }

    /**
     * Add prescription
     *
     * @param \PatientBundle\Entity\Prescription $prescription
     *
     * @return Patient
     */
    public function addPrescription(\PatientBundle\Entity\Prescription $prescription)
    {
        $this->prescriptions[] = $prescription;

        return $this;
    }

    public function setPrescriptions($prescription)
    {
        $this->prescriptions = $prescription;

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
     * Get prescription
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrescriptions()
    {
        return $this->prescriptions;
    }

    /**
     * Set doctor
     *
     * @param \DoctorBundle\Entity\Doctor $doctor
     *
     * @return Patient
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
     * Get doctor
     *
     * @return \DoctorBundle\Entity\Doctor
     */
    public function getDoctorName()
    {
        if ($this->getDoctor() instanceof Doctor) {
            return $this->getDoctor()->getName();
        }
        return null;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Patient
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set mailingAddress
     *
     * @param string $mailingAddress
     *
     * @return Patient
     */
    public function setMailingAddress($mailingAddress)
    {
        $this->mailingAddress = $mailingAddress;

        return $this;
    }

    /**
     * Get mailingAddress
     *
     * @return string
     */
    public function getMailingAddress()
    {
        return $this->mailingAddress;
    }

    /**
     * Set deliveryAddress
     *
     * @param string $deliveryAddress
     *
     * @return Patient
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get deliveryAddress
     *
     * @return string
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Patient
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set preferredContactMethod
     *
     * @param string $preferredContactMethod
     *
     * @return Patient
     */
    public function setPreferredContactMethod($preferredContactMethod)
    {
        $this->preferredContactMethod = $preferredContactMethod;

        return $this;
    }

    /**
     * Get preferredContactMethod
     *
     * @return string
     */
    public function getPreferredContactMethod()
    {
        return $this->preferredContactMethod;
    }

    /**
     * Set taxExemption
     *
     * @param string $taxExemption
     *
     * @return Patient
     */
    public function setTaxExemption($taxExemption)
    {
        $this->taxExemption = $taxExemption;

        return $this;
    }

    /**
     * Get taxExemption
     *
     * @return string
     */
    public function getTaxExemption()
    {
        return $this->taxExemption;
    }

    /**
     * Set diagnosis
     *
     * @param string $diagnosis
     *
     * @return Patient
     */
    public function setDiagnosis($diagnosis)
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    /**
     * Get diagnosis
     *
     * @return string
     */
    public function getDiagnosis()
    {
        return $this->diagnosis;
    }

    /**
     * Set onlineAccount
     *
     * @param boolean $onlineAccount
     *
     * @return Patient
     */
    public function setOnlineAccount($onlineAccount)
    {
        $this->onlineAccount = $onlineAccount;

        return $this;
    }

    /**
     * Get onlineAccount
     *
     * @return boolean
     */
    public function getOnlineAccount()
    {
        return $this->onlineAccount;
    }

    /**
     * Set lastVisit
     *
     * @param \DateTime $lastVisit
     *
     * @return Patient
     */
    public function setLastVisit($lastVisit)
    {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    /**
     * Get lastVisit
     *
     * @return \DateTime
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * Set memberSince
     *
     * @param \DateTime $memberSince
     *
     * @return Patient
     */
    public function setMemberSince($memberSince)
    {
        $this->memberSince = $memberSince;

        return $this;
    }

    /**
     * Get memberSince
     *
     * @return \DateTime
     */
    public function getMemberSince()
    {
        return $this->memberSince;
    }

    /**
     * Set memberExpire
     *
     * @param \DateTime $memberExpire
     *
     * @return Patient
     */
    public function setMemberExpire($memberExpire)
    {
        $this->memberExpire = $memberExpire;

        return $this;
    }

    /**
     * Get memberExpire
     *
     * @return \DateTime
     */
    public function getMemberExpire()
    {
        return $this->memberExpire;
    }

    /**
     * Add notesAndTask
     *
     * @param \PatientBundle\Entity\NoteAndTask $notesAndTask
     *
     * @return Patient
     */
    public function addNotesAndTask(\PatientBundle\Entity\NoteAndTask $notesAndTask)
    {
        $this->notesAndTasks[] = $notesAndTask;

        return $this;
    }

    /**
     * Add file
     *
     * @param \PatientBundle\Entity\File $file
     *
     * @return Patient
     */
    public function addFile($file)
    {
        if($file instanceof UploadedFile){
            $file = (new File)
                ->setFile($file)
                ->setPatient($this)
            ;
            $this->files[] = $file;
        }
        elseif($file instanceof File){
            $this->files[] = $file;
        }
        else{
            throw new InvalidArgumentException('Class ' . get_class($file));
        }

        return $this;
    }

    /**
     * Remove file
     *
     * @param \PatientBundle\Entity\File $file
     */
    public function removeFile(\PatientBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set accountManager
     *
     * @param \StaffBundle\Entity\Employee $accountManager
     *
     * @return Patient
     */
    public function setAccountManager(\StaffBundle\Entity\Employee $accountManager = null)
    {
        $this->accountManager = $accountManager;

        return $this;
    }

    /**
     * Get accountManager
     *
     * @return \StaffBundle\Entity\Employee
     */
    public function getAccountManager()
    {
        return $this->accountManager;
    }
}
