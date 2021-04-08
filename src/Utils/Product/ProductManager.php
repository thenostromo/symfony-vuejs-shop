<?php

namespace App\Utils\Product;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\FileSystem\FileSystemWorker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ProductManager
{
    /**
     * @var FileSystemWorker
     */
    private $fileSystemWorker;

    /**
     * @var ImageResizer
     */
    private $imageResizer;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $uploadsTempDir;

    /**
     * @var string
     */
    private $imagesProductsDir;

    public function __construct(
        FileSystemWorker $fileSystemWorker,
        ImageResizer $imageResizer,
        EntityManagerInterface $entityManager,
        string $uploadsTempDir,
        string $imagesProductsDir
    ) {
        $this->fileSystemWorker = $fileSystemWorker;
        $this->imageResizer = $imageResizer;
        $this->entityManager = $entityManager;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->imagesProductsDir = $imagesProductsDir;
    }

    public function updateProductImages(Product $product, string $filename = null)
    {
        if (!$filename) {
            return $product;
        }

        $productDir = sprintf('%s/%s', $this->imagesProductsDir, $product->getId());

        /*   $filesystem = new Filesystem();
           $filesystem->remove($productDir);*/
        $this->fileSystemWorker->createFolderIfNotExist($this->imagesProductsDir);
        $this->fileSystemWorker->createFolderIfNotExist($productDir);

        $fileNameId = uniqid();
        $params = [
            'width' => 60,
            'height' => 0,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'small'),
        ];
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $filename, $params);

        $params = [
            'width' => 430,
            'height' => 0,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'medium'),
        ];
        $imageMedium = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $filename, $params);

        $params = [
            'width' => 800,
            'height' => 0,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'big'),
        ];
        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $filename, $params);

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMedium);
        $productImage->setFilenameBig($imageBig);

        $this->entityManager->persist($productImage);

        $product->addProductImage($productImage);

        $originalFilePath = $this->uploadsTempDir.'/'.$filename;
        //$this->fileSystemWorker->remove($originalFilePath);

        return $product;
    }
}
