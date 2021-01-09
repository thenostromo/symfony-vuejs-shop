<?php

namespace App\Utils\Product;

use App\Entity\Product;
use App\Utils\File\ImageResizer;
use App\Utils\FileSystem\FolderWorker;
use Symfony\Component\Filesystem\Filesystem;

class ProductManager
{
    /**
     * @var FolderWorker
     */
    private $folderWorker;

    /**
     * @var ImageResizer
     */
    private $imageResizer;

    /**
     * @var string
     */
    private $uploadsTempDir;

    /**
     * @var string
     */
    private $imagesProductsDir;

    public function __construct(FolderWorker $folderWorker, ImageResizer $imageResizer, string $uploadsTempDir, string $imagesProductsDir)
    {
        $this->folderWorker = $folderWorker;
        $this->imageResizer = $imageResizer;
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
        $this->folderWorker->createFolderIfNotExist($this->imagesProductsDir);
        $this->folderWorker->createFolderIfNotExist($productDir);

        $fileNameId = uniqid();
        $params = [
            'width' => 60,
            'height' => 40,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'small')
        ];
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $filename, $params);

        $params = [
            'width' => 280,
            'height' => 280,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'medium')
        ];
        $imageMedium = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $filename, $params);

        $params = [
            'width' => 800,
            'height' => 533,
            'newFolder' => $productDir,
            'newFileName' => sprintf('%s_%s.jpg', $fileNameId, 'large')
        ];
        $imageLarge = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $filename, $params);

        $product->setSmallImages(json_encode([$imageSmall]));
        $product->setMediumImages(json_encode([$imageMedium]));
        $product->setLargeImages(json_encode([$imageLarge]));

        return $product;
    }
}