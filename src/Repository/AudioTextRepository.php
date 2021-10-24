<?php

namespace App\Repository;

use App\Entity\AudioText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AudioText|null find($id, $lockMode = null, $lockVersion = null)
 * @method AudioText|null findOneBy(array $criteria, array $orderBy = null)
 * @method AudioText[]    findAll()
 * @method AudioText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudioTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AudioText::class);
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(AudioText $audioText): AudioText
    {
        $this->getEntityManager()->persist($audioText);
        $this->getEntityManager()->flush();

        return $audioText;
    }

    // /**
    //  * @return AudioText[] Returns an array of AudioText objects
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
    public function findOneBySomeField($value): ?AudioText
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
