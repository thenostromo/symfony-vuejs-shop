<?php

namespace App\Controller\Main;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
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
    public function show(Category $category = null, ProductRepository $productRepository): Response
    {
        if (!$category) {
            throw new NotFoundHttpException();
        }

        return $this->render('main/category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
