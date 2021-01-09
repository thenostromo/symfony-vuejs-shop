<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Form\CategoryEditFormType;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
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
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orderList = $orderRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/order/list.html.twig', [
            'orderList' => $orderList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, Order $order = null): Response
    {
        if (!$order) {
            $order = new Order();
        }

        $form = $this->createForm(OrderEditFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('admin_order_list');
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'orderEditForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Order $order): Response
    {
        if ($order) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_order_list');
    }
}
