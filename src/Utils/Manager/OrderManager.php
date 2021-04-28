<?php

namespace App\Utils\Manager;

use App\Entity\Order;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderManager extends AbstractBaseManager
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    /**
     * @param Request $request
     *
     * @return PaginationInterface
     */
    public function paginateItems(Request $request): PaginationInterface
    {
        $query = $this->entityManager->getRepository(Order::class)
            ->createQueryBuilder('o')
            ->where('o.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false)
            ->getQuery();

        return $this->paginator->paginate($query, $request->query->getInt('page', 1));
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity): void
    {
        $entity->setIsDeleted(true);
        $this->save($entity);
    }
}
