<?php

namespace App\Controller\Main;

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

        if (!$saleCollection || !$saleCollection->getIsPublished()) {
            throw new NotFoundHttpException();
        }

        return $this->render('main/sale-collection/show.html.twig', [
            'saleCollection' => $saleCollection,
        ]);
    }
}
