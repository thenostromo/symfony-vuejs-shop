<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\ProductEditModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;

class ProductFormHandler
{
    /**
     * @var ProductManager
     */
    private $productManager;

    /**
     * @var FileSaver
     */
    private $fileSaver;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->productManager = $productManager;
        $this->fileSaver = $fileSaver;
    }

    /**
     * @param ProductEditModel $productEditModel
     *
     * @return Product
     */
    public function processEditForm(ProductEditModel $productEditModel): Product
    {
        $product = new Product();

        if ($productEditModel->id) {
            $product = $this->productManager->find($productEditModel->id);
        }

        $product->setTitle($productEditModel->title);
        $product->setPrice($productEditModel->price);
        $product->setQuantity($productEditModel->quantity);
        $product->setDescription($productEditModel->description);
        $product->setSize($productEditModel->size);
        $product->setCategory($productEditModel->category);
        $product->setIsPublished($productEditModel->isPublished);

        $this->productManager->save($product);

        $newImageFile = $productEditModel->newImage;

        $tempImageFileName = $productEditModel->newImage
            ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
            : null;

        $this->productManager->updateProductImages($product, $tempImageFileName);
        $this->productManager->save($product);

        return $product;
    }
}
