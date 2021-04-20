<?php

namespace App\Utils\Manager;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager
{
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $id
     * @return Order|null
     */
    public function findOrder(string $id): Order
    {
        return $this->entityManager->getRepository(Order::class)->find($id);
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(Order $entity)
    {
        $entity->setIsDeleted(true);
        $this->save($entity);
    }
}
