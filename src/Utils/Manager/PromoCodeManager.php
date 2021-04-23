<?php

namespace App\Utils\Manager;

use App\Entity\PromoCode;

class PromoCodeManager extends AbstractBaseManager
{
    /**
     * @param string $id
     *
     * @return PromoCode|null
     */
    public function find(string $id): ?PromoCode
    {
        return $this->entityManager->getRepository(PromoCode::class)->find($id);
    }

    public function remove($entity): void
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
