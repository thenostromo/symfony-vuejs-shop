<?php

namespace App\Controller;

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

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
