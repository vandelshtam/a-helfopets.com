<?php

namespace App\Repository;

use App\Entity\OurMission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OurMission|null find($id, $lockMode = null, $lockVersion = null)
 * @method OurMission|null findOneBy(array $criteria, array $orderBy = null)
 * @method OurMission[]    findAll()
 * @method OurMission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OurMissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OurMission::class);
    }

    // /**
    //  * @return OurMission[] Returns an array of OurMission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OurMission
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
