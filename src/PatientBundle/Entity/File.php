<?php

namespace PatientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use ITM\ExtensionsBundle\Annotation as ITMExt;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="PatientBundle\Entity\Repository\FileRepository")
 */
class File implements \JsonSerializable
{
    use Timestampable;

    const BASE_URL = "/patient/";

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
     * @ITMExt\UploadableField(path="patient")
     * @ORM\Column(name="file", type="string")
     */
    private $file;

    /**
     * @var string
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var int
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="PatientBundle\Entity\Patient", inversedBy="files")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patient;

    public function jsonSerialize()
    {
        return [
            'id'          => $this->getId(),
            'file'        => $this->getFile(),
            'title'       => $this->getTitle(),
            'size'        => $this->getSize(),
            'description' => $this->getDescription(),
            'url'         => self::BASE_URL . $this->getFile(),
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
     * Set file
     *
     * @param string $file
     *
     * @return File
     */
    public function setFile($file)
    {
        $this->file = $file;
        if($this->file instanceof UploadedFile){
            $this->setTitle($this->file->getClientOriginalName());
            $this->setSize($this->file->getSize());
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return File
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
     * Set patient
     *
     * @param \PatientBundle\Entity\Patient $patient
     *
     * @return File
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
     * @return File
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
     * Set size
     *
     * @param int $size
     *
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return \int
     */
    public function getSize()
    {
        return $this->size;
    }
}
