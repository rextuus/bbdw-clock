<?php

namespace App\Discography\Content\Lyric;

use App\Common\Entity\AbstractBaseRepository;
use App\Entity\Lyric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lyric>
 */
class LyricRepository extends AbstractBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lyric::class);
    }

    //    /**
    //     * @return Lyric[] Returns an array of Lyric objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lyric
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @param array<Lyric> $lyrics
     */
    public function persistLyrics(array $lyrics): void
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();
        foreach ($lyrics as $lyric) {
            $em->persist($lyric);
        }

        $em->flush();
    }

    public function getRandomLyric(bool $excludeDuplicates): ?Lyric
    {
        $qb = $this->createQueryBuilder('l');

        $qb->leftJoin('l.gameRounds', 'gr')
        ->where('gr is NULL');

        if($excludeDuplicates) {
            $qb->groupBy('l.id');
        }

        $ids = array_map('current', $qb->getQuery()->getScalarResult());

        if (empty($ids)) {
            return null;
        }

        $randomId = $ids[array_rand($ids)];

        return $this->getEntityManager()->find(Lyric::class, $randomId);
    }
}
