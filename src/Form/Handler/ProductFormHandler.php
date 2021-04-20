<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\ProductEditModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductFormHandler
{
    /**
     * @var ProductManager
     */
    public $productManager;

    /**
     * @var FileSaver
     */
    public $fileSaver;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->productManager = $productManager;
        $this->fileSaver = $fileSaver;
    }

    /**
     * @param ProductEditModel $productEditModel
     */
    public function processProductEditForm(ProductEditModel $productEditModel)
    {
        $product = new Product();

        if ($productEditModel->id) {
            $product = $this->productManager->findProduct($productEditModel->id);
        }

        $product->setTitle($productEditModel->title);
        $product->setPrice($productEditModel->price);
        $product->setQuantity($productEditModel->quantity);
        $product->setDescription($productEditModel->description);
        $product->setSize($productEditModel->size);
        $product->setCategory($productEditModel->category);
        $product->setIsPublished($productEditModel->isPublished);

        $this->productManager->save($product);

        /** @var UploadedFile $newImageFile */
        $newImageFile = $productEditModel->newImage;

        $tempImageFileName = $productEditModel->newImage
            ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
            : null;

        $this->productManager->updateProductImages($product, $tempImageFileName);
        $this->productManager->save($product);

        return $product;
    }
}
