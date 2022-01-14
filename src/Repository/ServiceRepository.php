<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByIdJoinedToCategory(int $serviceId): ?Service
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, c
            FROM App\Entity\Service p
            INNER JOIN p.category c
            WHERE p.id = :id'
        )->setParameter('id', $serviceId);

        return $query->getOneOrNullResult();
    }

    public function findByExampleField()
    {
        return $this->createQueryBuilder('p')
           // ->andWhere('a.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }
}
