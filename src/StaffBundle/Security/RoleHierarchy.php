<?php

namespace StaffBundle\Security;

use Doctrine\ORM\EntityManager;
use StaffBundle\Entity\AccessLevel;

class RoleHierarchy extends \Symfony\Component\Security\Core\Role\RoleHierarchy
{
    private $em;

    /**
     * @param array $hierarchy
     * @param EntityManager $em
     */
    public function __construct(array $hierarchy, EntityManager $em)
    {
        $this->em = $em;
        parent::__construct($this->buildRolesTree());
    }

    /**
     * Here we build an array with roles. It looks like a two-levelled tree - just
     * like original Symfony roles are stored in security.yml
     * @return array
     */
    private function buildRolesTree()
    {
        $hierarchy = array();
        $accessLevels = $this->em->createQuery('select al from StaffBundle:AccessLevel al')->execute();
        foreach($accessLevels as $accessLevel){
            if($accessLevel instanceof AccessLevel){
                if($accessLevel->getParent()){
                    $hierarchy[$accessLevel->getParent()->getRole()][] = $accessLevel->getRole();
                }
            }
        }

        return $hierarchy;
    }
}