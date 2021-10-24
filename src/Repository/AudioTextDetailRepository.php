<?php

namespace App\Repository;

use App\Entity\AudioTextDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AudioTextDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method AudioTextDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method AudioTextDetail[]    findAll()
 * @method AudioTextDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudioTextDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AudioTextDetail::class);
    }

    // /**
    //  * @return AudioTextDetail[] Returns an array of AudioTextDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AudioTextDetail
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
