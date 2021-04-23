<?php

namespace App\Utils\Manager;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractBaseManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $id
     * @return object|null
     */
    abstract public function find(string $id);

    /**
     * @param object $entity
     */
    public function save(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
