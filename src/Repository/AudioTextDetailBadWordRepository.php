<?php

namespace App\Repository;

use App\Entity\AudioTextDetailBadWord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AudioTextDetailBadWord|null find($id, $lockMode = null, $lockVersion = null)
 * @method AudioTextDetailBadWord|null findOneBy(array $criteria, array $orderBy = null)
 * @method AudioTextDetailBadWord[]    findAll()
 * @method AudioTextDetailBadWord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudioTextDetailBadWordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AudioTextDetailBadWord::class);
    }

    // /**
    //  * @return AudioTextDetailBadWord[] Returns an array of AudioTextDetailBadWord objects
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
    public function findOneBySomeField($value): ?AudioTextDetailBadWord
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
