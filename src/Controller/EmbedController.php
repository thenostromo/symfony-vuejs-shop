<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EmbedController extends AbstractController
{
    /**
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     */
    public function menuCategories(CategoryRepository $categoryRepository, string $pageRoute = null, string $pageSlug = null): Response
    {
        $categories = $categoryRepository->findBy([
            'isHidden' => false,
            'isDeleted' => false,
        ], ['title' => 'ASC'], 5);

        return $this->render('embed/_menu_categories.html.twig', [
            'categories' => $categories,
            'pageRoute' => $pageRoute,
            'pageSlug' => $pageSlug,
        ]);
    }

    /**
     * @param ProductRepository $productRepository
     *
     * @return Response
     */
    public function similarProducts(ProductRepository $productRepository, int $count = 4, int $categoryId = null): Response
    {
        $params = [];
        if ($categoryId) {
            $params = ['category' => $categoryId];
        }
        $products = $productRepository->findBy($params, ['title' => 'ASC'], $count);
        $productsModel = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $images = $product->getProductImages()->getValues();

            $productsModel[] = [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'images' => $images,
                'category' => [
                    'id' => $product->getCategory()->getId(),
                    'title' => $product->getCategory()->getTitle(),
                    'slug' => $product->getCategory()->getSlug(),
                ],
            ];
        }

        return $this->render('embed/_similar_products.html.twig', [
            'productsModel' => $productsModel,
            'count' => $count,
        ]);
    }

    /**
     * @param SaleCollectionRepository $saleCollectionRepository
     *
     * @return Response
     */
    public function saleCollectionHeader(SaleCollectionRepository $saleCollectionRepository): Response
    {
        $saleCollection = $saleCollectionRepository->findOneBy([], ['id' => 'ASC']);

        return $this->render('embed/_sale_collection_header.html.twig', [
            'saleCollection' => $saleCollection,
        ]);
    }
}
