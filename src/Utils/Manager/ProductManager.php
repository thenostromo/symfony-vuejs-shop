<?php

namespace App\Utils\Manager;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;

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

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, ProductImageManager $productImageManager, string $imagesProductsDir)
    {
        parent::__construct($entityManager, $paginator);

        $this->productImageManager = $productImageManager;
        $this->imagesProductsDir = $imagesProductsDir;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity): void
    {
        $entity->setIsDeleted(true);
        $this->save($entity);
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
