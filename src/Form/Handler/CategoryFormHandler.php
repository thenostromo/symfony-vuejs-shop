<?php

namespace App\Form\Handler;

use App\Entity\Category;
use App\Form\DTO\CategoryEditModel;
use App\Utils\Manager\CategoryManager;

class CategoryFormHandler
{
    /**
     * @var CategoryManager
     */
    public $categoryManager;

    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * @param CategoryEditModel $categoryEditModel
     */
    public function processCategoryEditForm(CategoryEditModel $categoryEditModel): void
    {
        $category = new Category();

        if ($categoryEditModel->id) {
            $category = $this->categoryManager->findCategory($categoryEditModel->id);
        }

        $title = ucfirst(strtolower($categoryEditModel->title));
        $category->setTitle($title);

        $this->categoryManager->save($category);
    }
}
