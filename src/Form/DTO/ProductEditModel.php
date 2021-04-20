<?php

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ProductEditModel
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

    /**
     * @Assert\NotBlank(message="Please enter a title")
     * @Assert\GreaterThanOrEqual(value="0")
     * @var string
     */
    public $price;

    /**
     * @Assert\File(
     *     maxSize = "5024k",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid image")
     * )
     * @var UploadedFile
     */
    public $newImage;

    /**
     * @Assert\NotBlank(message="Please indicate the quantity")
     * @var int
     */
    public $quantity;

    /**
     * @Assert\NotBlank(message="Please enter a description")
     * @var string
     */
    public $description;

    /**
     * @Assert\NotBlank(message="Please enter a size")
     * @var string
     */
    public $size;

    /**
     * @Assert\NotBlank(message="Please select a category")
     * @var Category
     */
    public $category;

    /**
     * @var bool
     */
    public $isPublished;

    /**
     * @var bool
     */
    public $isDeleted;

    public static function makeFromProduct(?Product $product): self
    {
        $productEditModel = new self();
        if (!$product) {
            return $productEditModel;
        }

        $productEditModel->id = $product->getId();
        $productEditModel->title = $product->getTitle();
        $productEditModel->price = $product->getPrice();
        $productEditModel->quantity = $product->getQuantity();
        $productEditModel->description = $product->getDescription();
        $productEditModel->size = $product->getSize();
        $productEditModel->category = $product->getCategory();
        $productEditModel->isPublished = $product->getIsPublished();
        $productEditModel->isDeleted = $product->getIsDeleted();

        return $productEditModel;
    }
}
