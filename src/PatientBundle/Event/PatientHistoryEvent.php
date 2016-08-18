<?php

namespace PatientBundle\Event;

use PatientBundle\Entity\Patient;
use Symfony\Component\EventDispatcher\Event;

class PatientHistoryEvent extends Event
{
    /**
     * @var Patient
     */
    protected $patient;

    /**
     * @var string
     */
    protected $title;

    public function __construct(Patient $patient, $title)
    {
        $this->patient = $patient;
        $this->title   = $title;
    }

    public function getPatient()
    {
        return $this->patient;
    }

    public function getTitle()
    {
        return $this->title;
    }
}