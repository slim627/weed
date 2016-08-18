<?php

namespace StaffBundle\Security\Voter;

use StaffBundle\Entity\Employee;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SuperAdminVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if(!$token->getUser() instanceof Employee){
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // Allow anything for ROLE_SUPER_ADMIN like IDDQD_VOTER from JMS does
        if($token->getUser()->isSuperAdmin()){
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    public function supportsAttribute($attribute)
    {
        return true;
    }
    public function supportsClass($class)
    {
        return true;
    }
}