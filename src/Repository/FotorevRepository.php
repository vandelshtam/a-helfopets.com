<?php

namespace App\Repository;

use App\Entity\Fotorev;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fotorev|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fotorev|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fotorev[]    findAll()
 * @method Fotorev[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FotorevRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fotorev::class);
    }

    // /**
    //  * @return Fotorev[] Returns an array of Fotorev objects
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
    public function findOneBySomeField($value): ?Fotorev
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
