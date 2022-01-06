<?php

namespace App\Repository;

use App\Entity\Fotoreview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fotoreview|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fotoreview|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fotoreview[]    findAll()
 * @method Fotoreview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FotoreviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fotoreview::class);
    }

    // /**
    //  * @return Fotoreview[] Returns an array of Fotoreview objects
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

    /*
    public function findOneBySomeField($value): ?Fotoreview
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
