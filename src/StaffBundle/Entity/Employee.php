<?php

namespace StaffBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="StaffBundle\Entity\Repository\EmployeeRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class Employee extends BaseUser implements \JsonSerializable
{
    use Timestampable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $first_name
     *
     * @ORM\Column(name="first_name", nullable=true, type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string $last_name
     *
     * @ORM\Column(name="last_name", nullable=true, type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="AccessLevel")
     * @ORM\JoinColumn(name="access_level", nullable=true, referencedColumnName="id")
     * @Assert\NotBlank()
     **/
    protected $accessLevel;

    /**
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="supervisor_id", nullable=true, referencedColumnName="id")
     **/
    private $supervisor;

    /**
     * @var string $company
     *
     * @ORM\Column(name="company", nullable=true, type="string", length=255)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="employee", cascade={"all"}, orphanRemoval=true)
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\Patient", mappedBy="accountManager", cascade={"persist"})
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity="PatientBundle\Entity\NoteAndTask", mappedBy="assignedTo", cascade={"persist"})
     */
    private $notesAndTasks;

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'access_level' => $this->getAccessLevel(),
        ];
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
     * Set accessLevel
     *
     * @param \StaffBundle\Entity\AccessLevel $accessLevel
     *
     * @return Employee
     */
    public function setAccessLevel(\StaffBundle\Entity\AccessLevel $accessLevel = null)
    {
        $this->accessLevel = $accessLevel;

        $roles = [$accessLevel->getRole()];
        if($this->hasRole('ROLE_SUPER_ADMIN')){
            $roles[] = 'ROLE_SUPER_ADMIN';
        }
        $this->setRoles($roles);

        return $this;
    }

    /**
     * Get accessLevel
     *
     * @return \StaffBundle\Entity\AccessLevel
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    /**
     * Set supervisor
     *
     * @param \StaffBundle\Entity\Employee $supervisor
     *
     * @return Employee
     */
    public function setSupervisor(\StaffBundle\Entity\Employee $supervisor = null)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return \StaffBundle\Entity\Employee
     */
    public function getSupervisor()
    {
        return $this->supervisor;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Employee
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
     * @return Employee
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
     * Set company
     *
     * @param string $company
     *
     * @return Employee
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add notification
     *
     * @param \StaffBundle\Entity\Notification $notification
     *
     * @return Employee
     */
    public function addNotification(\StaffBundle\Entity\Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \StaffBundle\Entity\Notification $notification
     */
    public function removeNotification(\StaffBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     *
     * @return Employee
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
     * Add notesAndTask
     *
     * @param \PatientBundle\Entity\NoteAndTask $notesAndTask
     *
     * @return Employee
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
