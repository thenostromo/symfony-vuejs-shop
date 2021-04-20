<?php

namespace App\Utils\Manager;

use App\Entity\PromoCode;
use Doctrine\ORM\EntityManagerInterface;

class PromoCodeManager
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
     * @return PromoCode|null
     */
    public function findPromoCode(string $id): PromoCode
    {
        return $this->entityManager->getRepository(PromoCode::class)->find($id);
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function getFormattedValue(string $originalValue = null)
    {
        $uniqueValue = $originalValue ? $originalValue : uniqid();
        return strtoupper($uniqueValue);
    }
}
