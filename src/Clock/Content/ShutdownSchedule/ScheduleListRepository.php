<?php

namespace App\Clock\Content\ShutdownSchedule;

use App\Common\Entity\AbstractBaseRepository;
use App\Entity\ScheduleList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScheduleList>
 */
class ScheduleListRepository extends AbstractBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduleList::class);
    }
    public function add(ScheduleList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    //    /**
    //     * @return ScheduleList[] Returns an array of ScheduleList objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ScheduleList
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
