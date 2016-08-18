<?php

namespace PatientBundle\Entity\Repository;

/**
 * ComplaintRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComplaintRepository extends \Doctrine\ORM\EntityRepository
{
    public function countObjectsPerDay()
    {
        $today = (new \DateTime)->setTime(0,0,0);
        $cloneDate = clone $today;
        $nextday = $cloneDate->modify('+1 day');

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(c)')
            ->from('PatientBundle:Complaint', 'c')
            ->andWhere('c.createdAt >= :today')
            ->andWhere('c.createdAt < :nextday')
            ->setParameters(['today' => $today, 'nextday' => $nextday])
            ->getQuery()
            ->getSingleScalarResult();
    }
}
