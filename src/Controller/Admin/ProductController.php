<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\DTO\ProductEditModel;
use App\Form\Handler\ProductFormHandler;
use App\Form\ProductEditFormType;
use App\Repository\ProductRepository;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     */
    public function index(ProductRepository $productRepository): Response
    {
        $productList = $productRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/product/list.html.twig', [
            'productList' => $productList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/edit", name="edit_blank")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, ProductFormHandler $productFormHandler, Product $product = null): Response
    {
        $productEditModel = ProductEditModel::makeFromProduct($product);

        $form = $this->createForm(ProductEditFormType::class, $productEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->processProductEditForm($productEditModel);

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
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
     */
    public function delete(Product $product, ProductManager $productManager): Response
    {
        $productManager->remove($product);

        return $this->redirectToRoute('admin_product_list');
    }
}
