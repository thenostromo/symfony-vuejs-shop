<?php

namespace App\Controller;

use App\DataProvider\ProductDataProvider;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", methods="GET", name="product_show")
     */
    public function show(Product $product): Response
    {
        if (!$product) {
            throw new NotFoundHttpException();
        }

        $images = $product->getProductImages()->getValues();

        $productModel = [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity(),
            'images' => $images,
            'category' => [
                'id' => $product->getCategory()->getId(),
                'title' => $product->getCategory()->getTitle(),
                'slug' => $product->getCategory()->getSlug()
            ]
        ];

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'productSizeChoice' => ProductDataProvider::getSizeList(),
            'productModel' => $productModel
        ]);
    }
}
