<?php

namespace App\Repository;

use App\Entity\SaleCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SaleCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleCollection[]    findAll()
 * @method SaleCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleCollection::class);
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
