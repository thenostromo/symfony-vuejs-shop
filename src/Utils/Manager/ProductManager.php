<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager extends AbstractBaseManager
{
    /**
     * @var ProductImageManager
     */
    private $productImageManager;

    /**
     * @var string
     */
    private $imagesProductsDir;

    public function __construct(EntityManagerInterface $entityManager, ProductImageManager $productImageManager, string $imagesProductsDir)
    {
        parent::__construct($entityManager);

        $this->productImageManager = $productImageManager;
        $this->imagesProductsDir = $imagesProductsDir;
    }

    /**
     * @param Product $entity
     */
    public function remove($entity): void
    {
        $entity->setIsDeleted(true);

        $this->save($entity);
    }

    /**
     * @param string $id
     *
     * @return Product|null
     */
    public function find(string $id): ?Product
    {
        return $this->entityManager->getRepository(Product::class)->find($id);
    }

    /**
     * @param Product $product
     *
     * @return string
     */
    public function getImagesProductDir(Product $product): string
    {
        return sprintf('%s/%s', $this->imagesProductsDir, $product->getId());
    }

    /**
     * @param Product     $product
     * @param string|null $tempImageFileName
     *
     * @return Product
     */
    public function updateProductImages(Product $product, string $tempImageFileName = null): Product
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
