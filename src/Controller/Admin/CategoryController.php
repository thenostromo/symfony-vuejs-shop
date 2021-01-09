<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryEditFormType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categoryList = $categoryRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/category/list.html.twig', [
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, Category $category = null): Response
    {
        if (!$category) {
            $category = new Category();
        }

        $form = $this->createForm(CategoryEditFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'categoryEditForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Category $category): Response
    {
        if ($category) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_category_list');
    }
}
