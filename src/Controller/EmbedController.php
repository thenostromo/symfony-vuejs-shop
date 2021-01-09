<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EmbedController extends AbstractController
{
    /**
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function menuCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['title' => 'ASC'], 5);

        return $this->render('embed/_menu_categories.html.twig', [
            'categories' => $categories
        ]);
    }
}
