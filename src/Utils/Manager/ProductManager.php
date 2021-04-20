<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager
{
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * @var ProductImageManager
     */
    public $productImageManager;

    /**
     * @var string
     */
    private $imagesProductsDir;

    public function __construct(EntityManagerInterface $entityManager, ProductImageManager $productImageManager, string $imagesProductsDir)
    {
        $this->entityManager = $entityManager;
        $this->productImageManager = $productImageManager;
        $this->imagesProductsDir = $imagesProductsDir;
    }

    /**
     * @param string $id
     * @return Product|null
     */
    public function findProduct(string $id): Product
    {
        return $this->entityManager->getRepository(Product::class)->find($id);
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

    /**
     * @param Product $product
     * @return string
     */
    public function getImagesProductDir(Product $product)
    {
        return sprintf('%s/%s', $this->imagesProductsDir, $product->getId());
    }

    /**
     * @param Product $product
     * @param string|null $tempImageFileName
     * @return Product
     */
    public function updateProductImages(Product $product, string $tempImageFileName = null)
    {
        if (!$tempImageFileName) {
            return $product;
        }

        $productDir = $this->getImagesProductDir($product);

        /** @var ProductImage $productImage */
        $productImage = $this->productImageManager->saveProductImage($productDir, $tempImageFileName);
        $this->entityManager->persist($productImage);

        $product->addProductImage($productImage);

        return $product;
    }
}
