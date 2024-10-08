<?php

namespace App\Repository;

use App\Common\Entity\AbstractBaseRepository;
use App\Entity\Audio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Audio>
 */
class AudioRepository extends AbstractBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audio::class);
    }

    /**
     * @return array<Audio>
     */
    public function getImprovisationAudios(): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where($qb->expr()->like('a.identifier', ':identifier'))
            ->setParameter('identifier', '%win_impro%');

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Audio[] Returns an array of Audio objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Audio
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
