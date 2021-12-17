<?php

namespace App\Repository;

use App\Entity\FastConsultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FastConsultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method FastConsultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method FastConsultation[]    findAll()
 * @method FastConsultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FastConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FastConsultation::class);
    }

    // /**
    //  * @return FastConsultation[] Returns an array of FastConsultation objects
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
    public function findOneBySomeField($value): ?FastConsultation
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
