<?php

namespace PatientBundle\Entity;

use CommonBundle\Form\Type\TernaryChoiceType;
use CommonBundle\Utils\TernaryChoice;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NoteAndTask
 *
 * @ORM\Table(name="note_and_task")
 * @ORM\Entity(repositoryClass="PatientBundle\Entity\Repository\NoteAndTaskRepository")
 */
class NoteAndTask implements \JsonSerializable
{
    use Timestampable;

    CONST ACCOUNT_STATUS_NO     = 0;
    CONST ACCOUNT_STATUS_YES    = 1;
    CONST ACCOUNT_STATUS_YES_NO = 2;

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
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var string
     * @ORM\Column(name="action_type", type="integer")
     * @Assert\NotBlank()
     */
    private $actionType = TernaryChoiceType::YES_NO;

    /**
     * @var \DateTime
     * @ORM\Column(name="start_time", type="datetime")
     * @Assert\NotBlank()
     */
    private $startTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="end_time", type="datetime")
     * @Assert\NotBlank()
     */
    private $endTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="complete_date", type="datetime")
     * @Assert\NotBlank()
     */
    private $completeDate;

    /**
     * @var string
     * @ORM\Column(name="account_phone", type="string")
     * @Assert\NotBlank()
     */
    private $accountPhone;

    /**
     * @var string
     * @ORM\Column(name="contact_phone", type="string")
     * @Assert\NotBlank()
     */
    private $contactPhone;

    /**
     * @var string
     * @ORM\Column(name="account_status", type="integer")
     * @Assert\NotBlank()
     */
    private $accountStatus = TernaryChoiceType::YES_NO;

    /**
     * @var boolean
     * @ORM\Column(name="is_complete", type="boolean", options={"default" = 0})
     */
    private $isComplete = false;

    /**
     * @ORM\ManyToOne(targetEntity="StaffBundle\Entity\Employee", inversedBy="notesAndTasks")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $assignedTo;

    /**
     * @ORM\ManyToOne(targetEntity="PatientBundle\Entity\Complaint", inversedBy="notesAndTasks")
     * @ORM\JoinColumn(name="complaint_id", referencedColumnName="id")
     */
    private $complaint;

    /**
     * @ORM\ManyToOne(targetEntity="PatientBundle\Entity\Patient", inversedBy="notesAndTasks")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patient;

    public function jsonSerialize()
    {
        return [
            'id'            => $this->getId(),
            'code'          => $this->getCode(),
            'title'         => $this->getTitle(),
            'description'   => $this->getDescription(),
            'note'          => $this->getNote(),
            'actionType'    => $this->getActionType(),
            'startTime'     => $this->getStartTime()->getTimestamp(),
            'endTime'       => $this->getEndTime()->getTimestamp(),
            'completeDate'  => $this->getCompleteDate()->getTimestamp(),
            'accountPhone'  => $this->getAccountPhone(),
            'contactPhone'  => $this->getContactPhone(),
            'accountStatus' => $this->getAccountStatus(),
            'isComplete'    => $this->getIsComplete(),
            'assignedTo'    => $this->getAssignedTo(),
            'complaint'     => $this->getComplaint(),
            'createdAt'     => $this->getCreatedAt()->getTimestamp(),
        ];
    }

    public function __toString()
    {
        return $this->getCode() ? $this->getCode() : 'NoteAndTask';
    }

    public function getNameForHistory()
    {
        return 'Task '. $this->getCode();
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
     * Set patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     *
     * @return NoteAndTask
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
     * Set title
     *
     * @param string $title
     *
     * @return NoteAndTask
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
     * Set description
     *
     * @param string $description
     *
     * @return NoteAndTask
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
     * Set note
     *
     * @param string $note
     *
     * @return NoteAndTask
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
     * Set actionType
     *
     * @param string $actionType
     *
     * @return NoteAndTask
     */
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * Get actionType
     *
     * @return string
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return NoteAndTask
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return NoteAndTask
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set completeDate
     *
     * @param \DateTime $completeDate
     *
     * @return NoteAndTask
     */
    public function setCompleteDate($completeDate)
    {
        $this->completeDate = $completeDate;

        return $this;
    }

    /**
     * Get completeDate
     *
     * @return \DateTime
     */
    public function getCompleteDate()
    {
        return $this->completeDate;
    }

    /**
     * Set accountPhone
     *
     * @param string $accountPhone
     *
     * @return NoteAndTask
     */
    public function setAccountPhone($accountPhone)
    {
        $this->accountPhone = $accountPhone;

        return $this;
    }

    /**
     * Get accountPhone
     *
     * @return string
     */
    public function getAccountPhone()
    {
        return $this->accountPhone;
    }

    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     *
     * @return NoteAndTask
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Set accountStatus
     *
     * @param string $accountStatus
     *
     * @return NoteAndTask
     */
    public function setAccountStatus($accountStatus)
    {
        $this->accountStatus = $accountStatus;

        return $this;
    }

    /**
     * Get accountStatus
     *
     * @return string
     */
    public function getAccountStatus()
    {
        return $this->accountStatus;
    }

    /**
     * Set isComplete
     *
     * @param boolean $isComplete
     *
     * @return NoteAndTask
     */
    public function setIsComplete($isComplete)
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    /**
     * Get isComplete
     *
     * @return boolean
     */
    public function getIsComplete()
    {
        return $this->isComplete;
    }

    /**
     * Set complaint
     *
     * @param string $complaint
     *
     * @return NoteAndTask
     */
    public function setComplaint($complaint)
    {
        $this->complaint = $complaint;

        return $this;
    }

    /**
     * Get complaint
     *
     * @return string
     */
    public function getComplaint()
    {
        return $this->complaint;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return NoteAndTask
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set assignedTo
     *
     * @param \StaffBundle\Entity\Employee $assignedTo
     *
     * @return NoteAndTask
     */
    public function setAssignedTo(\StaffBundle\Entity\Employee $assignedTo = null)
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo
     *
     * @return \StaffBundle\Entity\Employee
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }
}
