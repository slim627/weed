<?php

namespace PatientBundle\Event;

use PatientBundle\Entity\History;

class PatientHistoryListener
{
    protected $entityManager;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
      $this->entityManager = $entityManager;
    }

    public function onEventWithPatient(PatientHistoryEvent $event) {

        $history = new History();
        $history->setPatient($event->getPatient());
        $history->setTitle($event->getTitle());

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }
}