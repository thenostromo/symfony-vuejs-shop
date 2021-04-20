<?php

namespace App\Form\DTO;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryEditModel
{
    /**
     * @var string
     */
    public $id;

    /**
     * @Assert\NotBlank(message="Please enter a title")
     * @var string
     */
    public $title;

    public static function makeFromCategory(?Category $category): self
    {
        $categoryEditModel = new self();
        if (!$category) {
            return $categoryEditModel;
        }

        $categoryEditModel->id = $category->getId();
        $categoryEditModel->title = $category->getTitle();

        return $categoryEditModel;
    }
}
