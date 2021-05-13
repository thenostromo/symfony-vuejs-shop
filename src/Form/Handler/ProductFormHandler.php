<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\ProductEditModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ProductManager $productManager, PaginatorInterface $paginator, FileSaver $fileSaver)
    {
        $this->productManager = $productManager;
        $this->paginator = $paginator;
        $this->fileSaver = $fileSaver;
    }

    /**
     * @param Request $request
     *
     * @return PaginationInterface
     */
    public function processProductFiltersForm(Request $request): PaginationInterface
    {
        $queryBuilder = $this->productManager->getRepository()
            ->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false);

        return $this->paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1)
        );
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
