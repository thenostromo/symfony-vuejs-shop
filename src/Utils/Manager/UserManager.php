<?php

namespace App\Utils\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
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
     *
     * @return User|null
     */
    public function findUser(string $id): User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function save($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(User $entity)
    {
        $entity->setIsDeleted(true);
        $this->save($entity);
    }
}
