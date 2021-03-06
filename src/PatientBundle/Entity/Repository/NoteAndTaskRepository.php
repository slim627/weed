<?php

namespace PatientBundle\Entity\Repository;

/**
 * NoteAndTaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NoteAndTaskRepository extends \Doctrine\ORM\EntityRepository
{
    public function countObjectsPerDay()
    {
        $today = (new \DateTime)->setTime(0,0,0);
        $cloneDate = clone $today;
        $nextday = $cloneDate->modify('+1 day');

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(n)')
            ->from('PatientBundle:NoteAndTask', 'n')
            ->andWhere('n.createdAt >= :today')
            ->andWhere('n.createdAt < :nextday')
            ->setParameters(['today' => $today, 'nextday' => $nextday])
            ->getQuery()
            ->getSingleScalarResult();
    }
}
