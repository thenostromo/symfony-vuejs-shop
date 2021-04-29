<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SaleCollection;
use App\Entity\SaleCollectionProduct;
use App\Form\AdminType\SaleCollectionEditFormType;
use App\Form\DTO\SaleCollectionEditModel;
use App\Form\Handler\SaleCollectionFormHandler;
use App\Repository\ProductRepository;
use App\Repository\SaleCollectionProductRepository;
use App\Repository\SaleCollectionRepository;
use App\Utils\Manager\SaleCollectionManager;
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
        $saleCollectionList = $saleCollectionRepository->findBy([], ['id' => 'DESC'], 50);

        return $this->render('admin/sale-collection/list.html.twig', [
            'saleCollectionList' => $saleCollectionList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     *
     * @param Request                   $request
     * @param SaleCollectionFormHandler $saleCollectionFormHandler
     * @param SaleCollection|null       $saleCollection
     *
     * @return Response
     */
    public function edit(Request $request, SaleCollectionFormHandler $saleCollectionFormHandler, SaleCollection $saleCollection = null): Response
    {
        $saleCollectionEditModel = SaleCollectionEditModel::makeFromSaleCollection($saleCollection);

        $form = $this->createForm(SaleCollectionEditFormType::class, $saleCollectionEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $saleCollectionFormHandler->processSaleCollectionEditForm($saleCollectionEditModel);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_sale_collection_edit', ['id' => $order->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please, check your form!');
        }

        return $this->render('admin/sale-collection/edit.html.twig', [
            'saleCollection' => $saleCollection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     *
     * @param SaleCollection        $saleCollection
     * @param SaleCollectionManager $saleCollectionManager
     *
     * @return Response
     */
    public function delete(SaleCollection $saleCollection, SaleCollectionManager $saleCollectionManager): Response
    {
        $saleCollectionManager->remove($saleCollection);

        return $this->redirectToRoute('admin_sale_collection_list');
    }
}
