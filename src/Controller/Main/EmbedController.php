<?php

namespace App\Controller\Main;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EmbedController extends AbstractController
{
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

        return $this->render('main/_embed/_similar_products.html.twig', [
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
        $saleCollection = $saleCollectionRepository->findOneBy(['isPublished' => true], ['id' => 'ASC']);

        return $this->render('main/_embed/_sale_collection_header.html.twig', [
            'saleCollection' => $saleCollection,
        ]);
    }

    /**
     * @param SaleCollectionRepository $saleCollectionRepository
     *
     * @return Response
     */
    public function footer(SaleCollectionRepository $saleCollectionRepository): Response
    {
        $saleCollectionList = $saleCollectionRepository->findBy(['isPublished' => true], ['id' => 'ASC']);

        return $this->render('main/_embed/_footer.html.twig', [
            'saleCollectionList' => $saleCollectionList,
        ]);
    }
}
