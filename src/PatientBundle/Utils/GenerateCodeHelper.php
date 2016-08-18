<?php

namespace PatientBundle\Utils;

use Doctrine\ORM\Event\PostFlushEventArgs;
use PatientBundle\Entity\Complaint;
use PatientBundle\Entity\NoteAndTask;
use PatientBundle\Entity\Patient;

/**
 * Created by PhpStorm.
 * User: alex chistov
 * Date: 26.2.16
 * Time: 15.29
 */
class GenerateCodeHelper
{
    protected $objects;

    protected $args;

    public function __construct($objects, PostFlushEventArgs $args)
    {
        $this->objects = $objects;
        $this->args    = $args;
    }

    public function generateCode()
    {
        $args = $this->getArgs();
        $manager = $args->getEntityManager();
        $date = (new \DateTime())->format('Y.m.d');

        foreach ($this->getObjects() as $entity) {

            switch (true) {
                case $entity instanceof Patient:
                    $count = $args->getEntityManager()
                        ->getRepository('PatientBundle:Patient')
                        ->countObjectsPerDay();

                    $str = str_pad($count, 10, 0, STR_PAD_LEFT);
                    $entity->setIdNumber('P'.$str);

                    break;

                case $entity instanceof Complaint:
                    $count = $args->getEntityManager()
                        ->getRepository('PatientBundle:Complaint')
                        ->countObjectsPerDay();

                    $str = $date.'.'.str_pad($count,3, 0, STR_PAD_LEFT);
                    $entity->setCode('C'.$str);
                    break;

                case $entity instanceof NoteAndTask:
                    $count = $args->getEntityManager()
                        ->getRepository('PatientBundle:NoteAndTask')
                        ->countObjectsPerDay();

                    $str = $date.'.'.str_pad($count,3, 0, STR_PAD_LEFT);
                    $entity->setCode('T'.$str);
                    break;
            }

            $manager->flush($entity);
        }
    }

    public function getObjects()
    {
        return $this->objects;
    }

    public function getArgs()
    {
        return $this->args;
    }
}