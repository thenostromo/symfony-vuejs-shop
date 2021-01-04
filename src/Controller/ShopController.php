<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/", name="shop_index")
     */
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    /**
     * @Route("/category/{slug}", methods="GET", name="shop_category")
     */
    public function category(Category $category): Response
    {
        dump($category);exit();
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }
}
