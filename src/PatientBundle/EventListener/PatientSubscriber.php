<?php
/**
 * Created by PhpStorm.
 * User: alex chistov
 * Date: 26.2.16
 * Time: 10.59
 */

namespace PatientBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use PatientBundle\Entity\Patient;
use PatientBundle\Utils\GenerateCodeHelper;

class PatientSubscriber implements EventSubscriber
{
    private $objects = [];

    public function getSubscribedEvents()
    {
        return [
            Events:: onFlush,
            Events:: postFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $entities = $args->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions();

        array_walk($entities, function($entity, $splObjHash){

            if ($entity instanceof Patient && !in_array($entity, $this->objects, true)) {
                $this->objects[$splObjHash] = $entity;

            }

        });

        array_walk($entities, function($entity, $splObjHash){
            if (!empty($this->object)) {
                if ($entity instanceof Patient &&  in_array($entity, $this->object, true)) {
                    unset($this->object[$splObjHash]);
                }
            }
        });
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (!empty($this->objects)) {
            $objects = $this->objects;
            $this->objects = [];

            $helper = new GenerateCodeHelper($objects, $args);

            $helper->generateCode();
        }
    }
}