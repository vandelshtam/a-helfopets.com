<?php

namespace App\Repository;

use App\Entity\Fotoblog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fotoblog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fotoblog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fotoblog[]    findAll()
 * @method Fotoblog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FotoblogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fotoblog::class);
    }

    // /**
    //  * @return Fotoblog[] Returns an array of Fotoblog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findOneBySomeField($value): ?Fotoblog
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // public function findByExampleField()
    // {
    //     return $this->createQueryBuilder('f')
    //        // ->andWhere('a.exampleField = :val')
    //         //->setParameter('val', $value)
    //         ->orderBy('f.id', 'ASC')
    //         ->setMaxResults(1)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    
}
