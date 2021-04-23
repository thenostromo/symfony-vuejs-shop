<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SaleCollection;
use App\Entity\SaleCollectionProduct;
use App\Form\AdminType\SaleCollectionEditFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleCollectionProductRepository;
use App\Repository\SaleCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sale-collection", name="admin_sale_collection_")
 */
class SaleCollectionController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(SaleCollectionRepository $saleCollectionRepository): Response
    {
        $saleCollectionList = $saleCollectionRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/sale-collection/list.html.twig', [
            'saleCollectionList' => $saleCollectionList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(
        Request $request,
        SaleCollection $saleCollection = null,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response {
        if (!$saleCollection) {
            $saleCollection = new SaleCollection();
        }

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);
        $categoryModels = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $categoryModels[] = [
                'id' => $category->getId(),
                'title' => $category->getTitle(),
            ];
        }

        $form = $this->createForm(SaleCollectionEditFormType::class, $saleCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saleCollectionSlugRaw = strtolower(
                preg_replace('/[^a-zA-Z0-9 ]+/', '', $saleCollection->getTitle())
            );
            $saleCollectionSlugRaw = trim($saleCollectionSlugRaw);
            $saleCollectionSlug = str_replace(' ', '-', $saleCollectionSlugRaw);
            $saleCollection->setSlug($saleCollectionSlug);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($saleCollection);
            $entityManager->flush();

            return $this->redirectToRoute('admin_sale_collection_list');
        }

        return $this->render('admin/sale-collection/edit.html.twig', [
            'saleCollection' => $saleCollection,
            'form' => $form->createView(),
            'categories' => $categoryModels,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(SaleCollection $saleCollection): Response
    {
        if ($saleCollection) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($saleCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_sale_collection_list');
    }

    /**
     * @Route("/get-products-by-category", name="get_products_by_category")
     */
    public function getProductsByCategory(Request $request, ProductRepository $productRepository): Response
    {
        $categoryId = intval($request->request->get('categoryId'));
        $products = $productRepository->findBy(['category' => $categoryId], ['id' => 'ASC']);
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'title' => sprintf(
                    '#%s %s / P: %s$ / Q: %s',
                    $product->getId(),
                    $product->getTitle(),
                    $product->getPrice(),
                    $product->getQuantity()
                ),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @Route("/add-product-to-sale-collection", name="add_product_to_sale_collection")
     */
    public function addProductToSaleCollection(Request $request, ProductRepository $productRepository, SaleCollectionRepository $saleCollectionRepository)
    {
        $saleCollectionId = intval($request->request->get('saleCollectionId'));
        $productId = intval($request->request->get('productId'));
        $discountAmount = floatval($request->request->get('discountAmount'));

        $product = $productRepository->find($productId);

        $saleCollection = $saleCollectionRepository->find($saleCollectionId);
        $saleCollectionProduct = new SaleCollectionProduct();
        $saleCollectionProduct->setSaleCollection($saleCollection);
        $saleCollectionProduct->setDiscountAmount($discountAmount);
        $saleCollectionProduct->setProduct($product);

        $saleCollection->addSaleCollectionProduct($saleCollectionProduct);
        $this->getDoctrine()->getManager()->persist($saleCollectionProduct);
        $this->getDoctrine()->getManager()->persist($saleCollection);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'success' => true,
            'data' => [],
        ]);
    }

    /**
     * @Route("/remove-product-from-sale-collection", name="remove_product_from_sale_collection")
     */
    public function removeProductFromSaleCollection(Request $request, SaleCollectionProductRepository $saleCollectionProductRepository, SaleCollectionRepository $saleCollectionRepository)
    {
        $saleCollectionId = intval($request->request->get('saleCollectionId'));
        $productId = intval($request->request->get('productId'));

        $saleCollection = $saleCollectionRepository->find($saleCollectionId);
        $saleCollectionProduct = $saleCollectionProductRepository->find($productId);

        $saleCollection->removeSaleCollectionProduct($saleCollectionProduct);

        $this->getDoctrine()->getManager()->persist($saleCollection);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'success' => true,
            'data' => [],
        ]);
    }

    /**
     * @Route("/get-products-by-sale-collection", name="add_products_by_sale_collection")
     */
    public function getProductsOfSaleCollection(Request $request, SaleCollectionRepository $saleCollectionRepository)
    {
        $saleCollectionId = intval($request->request->get('saleCollectionId'));

        $saleCollection = $saleCollectionRepository->find($saleCollectionId);
        $saleCollectionProducts = $saleCollection->getSaleCollectionProducts()->getValues();
        $data = [];
        /* @var SaleCollectionProduct $product */
        foreach ($saleCollectionProducts as $saleCollectionProduct) {
            $product = $saleCollectionProduct->getProduct();
            $data[] = [
                'id' => $saleCollectionProduct->getId(),
                'category' => [
                    'id' => $product->getCategory()->getId(),
                    'title' => $product->getCategory()->getTitle(),
                ],
                'title' => sprintf(
                    '#%s %s / P: %s$ / Q: %s',
                    $product->getId(),
                    $product->getTitle(),
                    $product->getPrice(),
                    $product->getQuantity()
                ),
                'discountAmount' => $saleCollectionProduct->getDiscountAmount(),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $data,
        ]);
    }
}
