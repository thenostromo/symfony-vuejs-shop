<?php

namespace App\Controller\Main;

use App\DataProvider\ProductDataProvider;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", methods="GET", name="product_show")
     * @Route("/product", methods="GET", name="product_show_blank")
     */
    public function show(Product $product): Response
    {
        if (!$product) {
            throw new NotFoundHttpException();
        }

        $images = $product->getProductImages()->getValues();

        $productModel = [
            'id' => $product->getId(),
            'title' => addslashes($product->getTitle()),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity(),
            'images' => $images,
            'category' => [
                'id' => $product->getCategory()->getId(),
                'title' => $product->getCategory()->getTitle(),
                'slug' => $product->getCategory()->getSlug(),
            ],
        ];

        return $this->render('main/product/show.html.twig', [
            'product' => $product,
            'productSizeChoice' => ProductDataProvider::getSizeList(),
            'productModel' => $productModel,
        ]);
    }
}
