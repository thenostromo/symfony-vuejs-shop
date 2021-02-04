<?php

namespace App\Utils\Product;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\FileSystem\FileSystemWorker;
use App\Utils\FileSystem\FolderWorker;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;

class ProductImageManager
{
    /**
     * @var FileSystemWorker
     */
    private $fileSystemWorker;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $imagesProductsDir;

    public function __construct(
        FileSystemWorker $fileSystemWorker,
        EntityManagerInterface $entityManager,
        string $imagesProductsDir
    ) {
        $this->fileSystemWorker = $fileSystemWorker;
        $this->entityManager = $entityManager;
        $this->imagesProductsDir = $imagesProductsDir;
    }

    /**
     * @param ProductImage $productImage
     */
    public function removeProductImage(ProductImage $productImage)
    {
        $productDir = sprintf('%s/%s', $this->imagesProductsDir, $productImage->getProduct()->getId());

        $filePageSmall = $productDir . '/' . $productImage->getFilenameSmall();
        $this->fileSystemWorker->remove($filePageSmall);

        $filePageMiddle = $productDir . '/' . $productImage->getFilenameMiddle();
        $this->fileSystemWorker->remove($filePageMiddle);

        $filePageBig = $productDir . '/' . $productImage->getFilenameBig();
        $this->fileSystemWorker->remove($filePageBig);

        $this->entityManager->remove($productImage);
        $this->entityManager->flush();
    }
}
