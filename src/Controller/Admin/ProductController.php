<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductEditFormType;
use App\Repository\ProductRepository;
use App\Utils\File\FileSaver;
use App\Utils\Product\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        $productList = $productRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/product/list.html.twig', [
            'productList' => $productList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, FileSaver $fileSaver, ProductManager $productManager, Product $product = null): Response
    {
        if (!$product) {
            $product = new Product();
        }

        $form = $this->createForm(ProductEditFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $coverFile = $form->get('cover')->getData();

            $coverFileName = $coverFile
                ? $fileSaver->saveUploadedFileIntoTemp($coverFile)
                : null;

            $product = $productManager->updateProductImages($product, $coverFileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_list');
        }

        $images = [];
        if ($product->getMediumImages()) {
            $images = json_decode($product->getMediumImages(), true);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'images' => $images,
            'productEditForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Product $product): Response
    {
        if ($product) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_product_list');
    }
}
