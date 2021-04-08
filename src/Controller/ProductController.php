<?php

namespace App\Controller;

use App\DataProvider\ProductDataProvider;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", methods="GET", name="product_show")
     * @Route("/product", methods="GET", name="product_show_blank")
     */
    public function show(Product $product): Response
    {
        if (!$product) {
            throw new NotFoundHttpException();
        }

        $images = $product->getProductImages()->getValues();

        $productModel = [
            'id' => $product->getId(),
            'title' => addslashes($product->getTitle()),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity(),
            'images' => $images,
            'category' => [
                'id' => $product->getCategory()->getId(),
                'title' => $product->getCategory()->getTitle(),
                'slug' => $product->getCategory()->getSlug(),
            ],
        ];

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'productSizeChoice' => ProductDataProvider::getSizeList(),
            'productModel' => $productModel,
        ]);
    }

    /**
     * @Route("/get-count-of-products-by-category", name="product_get_count_of_products_by_category")
     */
    public function getCountOfProductsByCategory(Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $categoryId = intval($request->request->get('categoryId'));
        $category = $categoryRepository->find($categoryId);
        $countOfProducts = $productRepository->getCountByCategory($category);
        $data = [
            'count' => $countOfProducts,
        ];

        return new JsonResponse([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @Route("/get-products-by-category", name="product_get_products_by_category")
     */
    public function getProductsByCategory(Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $categoryId = intval($request->request->get('categoryId'));
        $offset = intval($request->request->get('offset'));
        $limit = intval($request->request->get('limit'));
        $category = $categoryRepository->find($categoryId);
        $products = $productRepository->getProductsByCategory($category, $offset, $limit);
        $data = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $images = $product->getProductImages()->getValues();

            $productModel = [
                'id' => $product->getId(),
                'title' => str_replace("'", '', $product->getTitle()),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'images' => [],
                'category' => [
                    'id' => $product->getCategory()->getId(),
                    'title' => $product->getCategory()->getTitle(),
                    'slug' => $product->getCategory()->getSlug(),
                ],
            ];

            /** @var ProductImage $image */
            foreach ($images as $image) {
                $productModel['images'][] = [
                    'id' => $image->getId(),
                    'filenameBig' => $image->getFilenameBig(),
                    'filenameMiddle' => $image->getFilenameMiddle(),
                    'filenameSmall' => $image->getFilenameSmall(),
                ];
            }

            $data[] = $productModel;
        }

        return new JsonResponse([
            'success' => true,
            'data' => $data,
        ]);
    }
}
