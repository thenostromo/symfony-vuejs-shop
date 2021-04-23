<?php

namespace App\Utils\Manager;

use App\Entity\Category;

class CategoryManager extends AbstractBaseManager
{
    /**
     * @param string $id
     * @return ?Category
     */
    public function find(string $id): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->find($id);
    }
}
