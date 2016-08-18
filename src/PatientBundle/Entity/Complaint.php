<?php

namespace PatientBundle\Entity;

use CommonBundle\Utils\Priority;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use PatientBundle\Form\Type\ComplaintPriorityType;
use PatientBundle\Form\Type\ComplaintSourceType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Complaint
 *
 * @ORM\Table(name="complaint")
 * @ORM\Entity(repositoryClass="PatientBundle\Entity\Repository\ComplaintRepository")
 */
class Complaint implements \JsonSerializable
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
     * @ORM\Column(name="code", type="string", nullable=true)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(name="product_name", type="string", nullable=false)
     * @Assert\NotBlank()
     * * @Assert\Length(
     *      min = 6,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $productName;

    /**
     * @var string
     * @ORM\Column(name="source_of_complaint", type="integer")
     * @Assert\NotBlank()
     */
    private $sourceOfComplaint = ComplaintSourceType::PHONE;

    /**
     * @var string
     * @ORM\Column(name="received_by", type="string")
     * @Assert\NotBlank()
     */
    private $receivedBy;

    /**
     * @var string
     * @ORM\Column(name="lot_batch_number", type="string")
     * @Assert\NotBlank()
     */
    private $lotBatchNumber;

    /**
     * @var \DateTime
     * @ORM\Column(name="due_date", type="datetime")
     * @Assert\NotBlank()
     */
    private $dueDate;

    /**
     * @var integer
     * @ORM\Column(name="package_size", type="integer")
     * @Assert\NotBlank()
     */
    private $packageSize;

    /**
     * @var string
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_sample_received", type="datetime")
     * @Assert\NotBlank()
     */
    private $dateSampleReceived;

    /**
     * @var string
     * @ORM\Column(name="priority", type="integer")
     * @Assert\NotBlank()
     */
    private $priority = ComplaintPriorityType::NORMAL;

    /**
     * @var boolean
     * @ORM\Column(name="sample_available", type="boolean", options={"default" = 0})
     */
    private $sampleAvailable = false;

    /**
     * @var string
     * @ORM\Column(name="quantity_received", type="integer", nullable=true)
     */
    private $quantityReceived;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     * @ORM\Column(name="preferred_contact_method", type="integer")
     * @Assert\NotBlank()
     */
    private $preferredContactMethod;

    /**
     * @var string
     * @ORM\Column(name="assigned_to", type="string")
     * @Assert\NotBlank()
     */
    private $assignedTo;

    /**
     * @var integer
     * @ORM\Column(name="status", type="integer")
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="PatientBundle\Entity\Patient", inversedBy="complains")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patient;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\NoteAndTask", mappedBy="complaint", cascade={"all"}, orphanRemoval=true)
     */
    private $notesAndTasks;

    public function jsonSerialize()
    {
        return [
            'id'                     => $this->getId(),
            'code'                   => $this->getCode(),
            'productName'            => $this->getProductName(),
            'sourceOfComplaint'      => $this->getSourceOfComplaint(),
            'receivedBy'             => $this->getReceivedBy(),
            'lotBatchNumber'         => $this->getLotBatchNumber(),
            'dueDate'                => $this->getDueDate()->getTimestamp(),
            'packageSize'            => $this->getPackageSize(),
            'title'                  => $this->getTitle(),
            'dateSampleReceived'     => $this->getDateSampleReceived(),
            'priority'               => $this->getPriority(),
            'sampleAvailable'        => $this->getSampleAvailable(),
            'quantityReceived'       => $this->getQuantityReceived(),
            'description'            => $this->getDescription(),
            'preferredContactMethod' => $this->getPreferredContactMethod(),
            'assignedTo'             => $this->getAssignedTo(),
            'status'                 => $this->getStatus(),
            'note'                   => $this->getNote(),
            'createdAt'              => $this->getCreatedAt()->getTimestamp(),
        ];
    }

    public function __toString()
    {
        return $this->getCode() ? $this->getCode() : 'Complaint' ;
    }

    public function getNameForHistory()
    {
        return 'Complaint '. $this->getCode();
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
     * Set complaintId
     *
     * @param string $code
     *
     * @return Complaint
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get complaintId
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return Complaint
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set sourceOfComplaint
     *
     * @param string $sourceOfComplaint
     *
     * @return Complaint
     */
    public function setSourceOfComplaint($sourceOfComplaint)
    {
        $this->sourceOfComplaint = $sourceOfComplaint;

        return $this;
    }

    /**
     * Get sourceOfComplaint
     *
     * @return string
     */
    public function getSourceOfComplaint()
    {
        return $this->sourceOfComplaint;
    }

    /**
     * Set receivedBy
     *
     * @param string $receivedBy
     *
     * @return Complaint
     */
    public function setReceivedBy($receivedBy)
    {
        $this->receivedBy = $receivedBy;

        return $this;
    }

    /**
     * Get receivedBy
     *
     * @return string
     */
    public function getReceivedBy()
    {
        return $this->receivedBy;
    }

    /**
     * Set lotBatchNumber
     *
     * @param string $lotBatchNumber
     *
     * @return Complaint
     */
    public function setLotBatchNumber($lotBatchNumber)
    {
        $this->lotBatchNumber = $lotBatchNumber;

        return $this;
    }

    /**
     * Get lotBatchNumber
     *
     * @return string
     */
    public function getLotBatchNumber()
    {
        return $this->lotBatchNumber;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return Complaint
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set packageSize
     *
     * @param integer $packageSize
     *
     * @return Complaint
     */
    public function setPackageSize($packageSize)
    {
        $this->packageSize = $packageSize;

        return $this;
    }

    /**
     * Get packageSize
     *
     * @return integer
     */
    public function getPackageSize()
    {
        return $this->packageSize;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Complaint
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
     * Set dateSampleReceived
     *
     * @param \DateTime $dateSampleReceived
     *
     * @return Complaint
     */
    public function setDateSampleReceived($dateSampleReceived)
    {
        $this->dateSampleReceived = $dateSampleReceived;

        return $this;
    }

    /**
     * Get dateSampleReceived
     *
     * @return \DateTime
     */
    public function getDateSampleReceived()
    {
        return $this->dateSampleReceived;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return Complaint
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set sampleAvailable
     *
     * @param boolean $sampleAvailable
     *
     * @return Complaint
     */
    public function setSampleAvailable($sampleAvailable)
    {
        $this->sampleAvailable = $sampleAvailable;

        return $this;
    }

    /**
     * Get sampleAvailable
     *
     * @return boolean
     */
    public function getSampleAvailable()
    {
        return $this->sampleAvailable;
    }

    /**
     * Set quantityReceived
     *
     * @param integer $quantityReceived
     *
     * @return Complaint
     */
    public function setQuantityReceived($quantityReceived)
    {
        $this->quantityReceived = $quantityReceived;

        return $this;
    }

    /**
     * Get quantityReceived
     *
     * @return integer
     */
    public function getQuantityReceived()
    {
        return $this->quantityReceived;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Complaint
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set preferredContactMethod
     *
     * @param integer $preferredContactMethod
     *
     * @return Complaint
     */
    public function setPreferredContactMethod($preferredContactMethod)
    {
        $this->preferredContactMethod = $preferredContactMethod;

        return $this;
    }

    /**
     * Get preferredContactMethod
     *
     * @return integer
     */
    public function getPreferredContactMethod()
    {
        return $this->preferredContactMethod;
    }

    /**
     * Set assignedTo
     *
     * @param string $assignedTo
     *
     * @return Complaint
     */
    public function setAssignedTo($assignedTo)
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo
     *
     * @return string
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Complaint
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set notes
     *
     * @param string $note
     *
     * @return Complaint
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     *
     * @return Complaint
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
     * Constructor
     */
    public function __construct()
    {
        $this->notesAndTasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notesAndTask
     *
     * @param \PatientBundle\Entity\NoteAndTask $notesAndTask
     *
     * @return Complaint
     */
    public function addNotesAndTask(\PatientBundle\Entity\NoteAndTask $notesAndTask)
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
}
