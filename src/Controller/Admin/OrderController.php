<?php

namespace App\Controller\Admin;

use App\DataProvider\OrderDataProvider;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\AdminType\OrderEditFormType;
use App\Form\DTO\OrderEditModel;
use App\Form\Handler\OrderFormHandler;
use App\Repository\CategoryRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orderList = $orderRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/order/list.html.twig', [
            'orderList' => $orderList,
            'statusList' => OrderDataProvider::getStatusList(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, CategoryRepository $categoryRepository, OrderFormHandler $orderFormHandler, Order $order = null): Response
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
