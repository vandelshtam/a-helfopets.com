<?php

namespace App\Repository;

use App\Entity\Ratingblog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ratingblog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ratingblog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ratingblog[]    findAll()
 * @method Ratingblog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingblogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ratingblog::class);
    }

    // /**
    //  * @return Ratingblog[] Returns an array of Ratingblog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ratingblog
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
