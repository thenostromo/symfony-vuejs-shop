<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", methods="GET", name="category_show")
     */
    public function show(Category $category, ProductRepository $productRepository): Response
    {
        if (!$category) {
            throw new NotFoundHttpException();
        }

        $products = $productRepository->findBy(['category' => $category]);
        $productsModel = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $productsModel[] = [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'cover' => $product->getCover(),
                'category' => [
                    'id' => $product->getCategory()->getId(),
                    'title' => $product->getCategory()->getTitle(),
                    'slug' => $product->getCategory()->getSlug()
                ]
            ];
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'products' => $products,
            'productsModel' => $productsModel
        ]);
    }
}
