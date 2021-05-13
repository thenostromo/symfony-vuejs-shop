<?php

namespace App\Controller\Admin;

use App\DataProvider\OrderDataProvider;
use App\Entity\Order;
use App\Form\AdminType\FilterType\OrderFilterFormType;
use App\Form\AdminType\OrderEditFormType;
use App\Form\DTO\FilterType\OrderFilterModel;
use App\Form\DTO\OrderEditModel;
use App\Form\Handler\OrderFormHandler;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order", name="admin_order_")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     *
     * @param OrderFormHandler $orderFormHandler
     * @param Request          $request
     *
     * @return Response
     */
    public function index(OrderFormHandler $orderFormHandler, Request $request): Response
    {
        $orderFilterModel = new OrderFilterModel();

        $filterForm = $this->createForm(OrderFilterFormType::class, $orderFilterModel);
        $filterForm->handleRequest($request);

        $pagination = $orderFormHandler->processOrderFiltersForm($request, $filterForm);

        return $this->render('admin/order/list.html.twig', [
            'statusList' => OrderDataProvider::getStatusList(),
            'pagination' => $pagination,
            'form' => $filterForm->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     *
     * @param Request          $request
     * @param OrderFormHandler $orderFormHandler
     * @param Order|null       $order
     *
     * @return Response
     */
    public function edit(Request $request, OrderFormHandler $orderFormHandler, Order $order = null): Response
    {
        $orderEditModel = OrderEditModel::makeFromOrder($order);

        $form = $this->createForm(OrderEditFormType::class, $orderEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $orderFormHandler->processOrderEditForm($orderEditModel);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_order_edit', ['id' => $order->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please, check your form!');
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     *
     * @param Order        $order
     * @param OrderManager $orderManager
     *
     * @return Response
     */
    public function delete(Order $order, OrderManager $orderManager): Response
    {
        $orderManager->remove($order);

        return $this->redirectToRoute('admin_order_list');
    }
}
