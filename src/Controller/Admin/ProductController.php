<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\AdminType\ProductEditFormType;
use App\Form\DTO\ProductEditModel;
use App\Form\Handler\ProductFormHandler;
use App\Utils\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product", name="admin_product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     *
     * @param ProductFormHandler $productFormHandler
     * @param Request            $request
     *
     * @return Response
     */
    public function index(ProductFormHandler $productFormHandler, Request $request): Response
    {
        $pagination = $productFormHandler->processProductFiltersForm($request);

        return $this->render('admin/product/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/edit", name="edit_blank")
     * @Route("/add", name="add")
     *
     * @param Request            $request
     * @param ProductFormHandler $productFormHandler
     * @param Product|null       $product
     *
     * @return Response
     */
    public function edit(Request $request, ProductFormHandler $productFormHandler, Product $product = null): Response
    {
        $productEditModel = ProductEditModel::makeFromProduct($product);

        $form = $this->createForm(ProductEditFormType::class, $productEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->processEditForm($productEditModel);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please, check your form!');
        }

        $images = $product ? $product->getProductImages()->getValues() : [];

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'images' => $images,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     *
     * @param Product        $product
     * @param ProductManager $productManager
     *
     * @return Response
     */
    public function delete(Product $product, ProductManager $productManager): Response
    {
        $productManager->remove($product);

        return $this->redirectToRoute('admin_product_list');
    }
}
