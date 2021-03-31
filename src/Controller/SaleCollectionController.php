<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\SaleCollectionProduct;
use App\Repository\ProductRepository;
use App\Repository\SaleCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SaleCollectionController extends AbstractController
{
    /**
     * @Route("/sale-collection/{slug}", methods="GET", name="sale_collection_show")
     */
    public function show(string $slug, SaleCollectionRepository $saleCollectionRepository, ProductRepository $productRepository): Response
    {
        $saleCollection = $saleCollectionRepository->findOneBy(['slug' => $slug]);

        if (!$saleCollection || $saleCollection->getIsHidden() || $saleCollection->getIsDeleted()) {
            throw new NotFoundHttpException();
        }


        $productsModel = [];

        /** @var SaleCollectionProduct $saleCollectionProduct */
        foreach ($saleCollection->getSaleCollectionProducts()->getValues() as $saleCollectionProduct) {
            $product = $saleCollectionProduct->getProduct();
            $images = $product->getProductImages()->getValues();

            $productModel = [
                'id' => $product->getId(),
                'title' => str_replace("'", "", $product->getTitle()),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'images' => [],
                'category' => [
                    'id' => $product->getCategory()->getId(),
                    'title' => $product->getCategory()->getTitle(),
                    'slug' => $product->getCategory()->getSlug()
                ]
            ];

            /** @var ProductImage $image */
            foreach ($images as $image) {
                $productModel['images'][] = [
                    'id' => $image->getId(),
                    'filenameBig' => $image->getFilenameBig(),
                    'filenameMiddle' => $image->getFilenameMiddle(),
                    'filenameSmall' => $image->getFilenameSmall()
                ];
            }

            $productsModel[] = $productModel;
        }

        $saleCollectionModel = [
            'id' => $saleCollection->getId(),
            'title' => $saleCollection->getTitle()
        ];

        return $this->render('category/show.html.twig', [
            'categoryModel' => $saleCollection,
            'category' => $saleCollection,
            'productsModel' => $productsModel
        ]);
    }
}
