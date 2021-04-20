<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\FileSystem\FileSystemWorker;
use Doctrine\ORM\EntityManagerInterface;

class ProductImageManager
{
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * @var FileSystemWorker
     */
    private $fileSystemWorker;

    /**
     * @var ImageResizer
     */
    private $imageResizer;

    /**
     * @var string
     */
    private $uploadsTempDir;

    public function __construct(EntityManagerInterface $entityManager, FileSystemWorker $fileSystemWorker, ImageResizer $imageResizer, string $uploadsTempDir)
    {
        $this->entityManager = $entityManager;
        $this->fileSystemWorker = $fileSystemWorker;
        $this->imageResizer = $imageResizer;
        $this->uploadsTempDir = $uploadsTempDir;
    }

    public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * @param string      $productDir
     * @param string|null $tempImageFileName
     *
     * @return Product|ProductImage
     */
    public function saveProductImage(string $productDir, string $tempImageFileName = null)
    {
        $this->fileSystemWorker->createFolderIfNotExist($productDir);

        $fileNameId = uniqid();
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFileName, [
            'width' => 60,
            'height' => 0,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'small'),
        ]);

        $imageMedium = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFileName, [
            'width' => 430,
            'height' => 0,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'medium'),
        ]);

        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFileName, [
            'width' => 800,
            'height' => 0,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'big'),
        ]);

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMedium);
        $productImage->setFilenameBig($imageBig);

        return $productImage;
    }

    /**
     * @param ProductImage $productImage
     * @param string       $productDir
     */
    public function removeProductImage(ProductImage $productImage, string $productDir)
    {
        $filePageSmall = $productDir.'/'.$productImage->getFilenameSmall();
        $this->fileSystemWorker->remove($filePageSmall);

        $filePageMiddle = $productDir.'/'.$productImage->getFilenameMiddle();
        $this->fileSystemWorker->remove($filePageMiddle);

        $filePageBig = $productDir.'/'.$productImage->getFilenameBig();
        $this->fileSystemWorker->remove($filePageBig);

        $this->remove($productImage);
    }
}
