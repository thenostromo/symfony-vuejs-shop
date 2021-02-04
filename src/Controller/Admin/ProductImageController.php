<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Form\ProductEditFormType;
use App\Repository\ProductRepository;
use App\Utils\File\FileSaver;
use App\Utils\Product\ProductImageManager;
use App\Utils\Product\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/product-image", name="admin_product_image_")
 */
class ProductImageController extends AbstractController
{
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(ProductImage $productImage, ProductImageManager $productImageManager): Response
    {
        if (!$productImage) {
            return $this->redirectToRoute('admin_product_list');
        }

        $productId = $productImage->getProduct()->getId();
        $productImageManager->removeProductImage($productImage);

        return $this->redirectToRoute('admin_product_edit', [
            'id' => $productId
        ]);
    }
}
