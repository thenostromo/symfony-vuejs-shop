<?php

namespace App\Utils\Manager;

use App\Entity\PromoCode;
use Doctrine\Persistence\ObjectRepository;

class PromoCodeManager extends AbstractBaseManager
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(PromoCode::class);
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity): void
    {
        $entity->setIsDeleted(true);
        $this->save($entity);
    }

    public function getFormattedValue(string $originalValue = null)
    {
        $uniqueValue = $originalValue ?: uniqid('', true);

        return strtoupper($uniqueValue);
    }
}
