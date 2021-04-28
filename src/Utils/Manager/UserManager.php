<?php

namespace App\Utils\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class UserManager extends AbstractBaseManager
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
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
