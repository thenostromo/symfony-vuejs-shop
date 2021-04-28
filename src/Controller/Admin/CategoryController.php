<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\AdminType\CategoryEditFormType;
use App\Form\DTO\CategoryEditModel;
use App\Form\Handler\CategoryFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\CategoryManager;
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
     *
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
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
     *
     * @param Request             $request
     * @param CategoryFormHandler $categoryFormHandler
     * @param Category|null       $category
     *
     * @return Response
     */
    public function edit(Request $request, CategoryFormHandler $categoryFormHandler, Category $category = null): Response
    {
        $categoryEditModel = CategoryEditModel::makeFromCategory($category);

        $form = $this->createForm(CategoryEditFormType::class, $categoryEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryFormHandler->processEditForm($categoryEditModel);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_category_list');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please, check your form!');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Category $category, CategoryManager $categoryManager): Response
    {
        $categoryManager->remove($category);

        return $this->redirectToRoute('admin_category_list');
    }
}
