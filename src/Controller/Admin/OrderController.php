<?php

namespace App\Controller\Admin;

use App\DataProvider\OrderDataProvider;
use App\Entity\Order;
use App\Form\AdminType\OrderEditFormType;
use App\Form\DTO\OrderEditModel;
use App\Form\Handler\OrderFormHandler;
use App\Repository\CategoryRepository;
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
     * @param OrderManager $orderManager
     * @param Request      $request
     *
     * @return Response
     */
    public function index(OrderManager $orderManager, Request $request): Response
    {
        $pagination = $orderManager->paginateItems($request);

        return $this->render('admin/order/list.html.twig', [
            'statusList' => OrderDataProvider::getStatusList(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, OrderFormHandler $orderFormHandler, Order $order = null): Response
    {
        $orderEditModel = OrderEditModel::makeFromOrder($order);

        $form = $this->createForm(OrderEditFormType::class, $orderEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $orderFormHandler->processOrderEditForm($orderEditModel);

            return $this->redirectToRoute('admin_order_edit', ['id' => $order->getId()]);
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Order $order, OrderManager $orderManager): Response
    {
        $orderManager->remove($order);

        return $this->redirectToRoute('admin_order_list');
    }
}
