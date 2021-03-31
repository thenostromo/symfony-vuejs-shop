<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param Category $category
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountByCategory(Category $category)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param Category $category
     * @param int $offset
     * @param int $limit
     * @return Product[]
     */
    public function getProductsByCategory(Category $category, int $offset = 0, int $limit = 0)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p')
            ->andWhere('p.category = :category')
            ->orderBy('p.id', 'ASC')
            ->setParameters([
                'category' => $category
            ])
            ->setFirstResult($offset);

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
