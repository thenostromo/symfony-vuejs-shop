<?php

namespace App\Repository;

use App\Entity\SaleCollectionProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SaleCollectionProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleCollectionProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleCollectionProduct[]    findAll()
 * @method SaleCollectionProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleCollectionProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleCollectionProduct::class);
    }

    // /**
    //  * @return SaleCollection[] Returns an array of SaleCollection objects
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

    /*
    public function findOneBySomeField($value): ?SaleCollection
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
