<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

     /**
      * @return Category[] Returns an array of Category objects
      */
    
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.service_id = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    // public function deleteOneByIdJoinedToCategory(int $serviceId): ?Service
    // {
    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery(
    //         'DELETE p, c
    //         FROM App\Entity\Service p
    //         WHERE p.id = :id'
    //     )->setParameter('id', $serviceId);

    //     return $query->getOneOrNullResult();
    // }


    
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    

    public function findOneByIdJoinedToService(int $categoryId): ?Category
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, c
            FROM App\Entity\Category p
            INNER JOIN p.services c
            WHERE p.id = :id'
        )->setParameter('id', $categoryId);

        return $query->getOneOrNullResult();
    }
    
}
