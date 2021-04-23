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

        $categories = $categoryRepository->findBy([], ['title' => 'ASC']);
        $categoryModels = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $categoryModels[] = [
                'id' => $category->getId(),
                'title' => $category->getTitle(),
            ];
        }

        $form = $this->createForm(OrderEditFormType::class, $orderEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $orderFormHandler->processOrderEditForm($orderEditModel);

            return $this->redirectToRoute('admin_order_edit', ['id' => $order->getId()]);
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
            'categories' => $categoryModels,
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

    /**
     * @Route("/add-product-to-order", name="add_product_to_order")
     */
    public function addProductToOrder(Request $request, ProductRepository $productRepository, OrderRepository $orderRepository)
    {
        $orderId = intval($request->request->get('orderId'));
        $productId = intval($request->request->get('productId'));
        $pricePerOne = floatval($request->request->get('pricePerOne'));
        $quantity = intval($request->request->get('quantity'));

        $product = $productRepository->find($productId);

        $order = $orderRepository->find($orderId);
        $orderProduct = new OrderProduct();
        $orderProduct->setAppOrder($order);
        $orderProduct->setPricePerOne($pricePerOne);
        $orderProduct->setQuantity($quantity);
        $orderProduct->setProduct($product);

        $order->addOrderProduct($orderProduct);
        $this->getDoctrine()->getManager()->persist($orderProduct);
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'success' => true,
            'data' => [],
        ]);
    }

    /**
     * @Route("/remove-product-from-order", name="remove_product_from_order")
     */
    public function removeProductFromOrder(Request $request, OrderProductRepository $orderProductRepository, OrderRepository $orderRepository)
    {
        $orderId = intval($request->request->get('orderId'));
        $productId = intval($request->request->get('productId'));

        $order = $orderRepository->find($orderId);
        $orderProduct = $orderProductRepository->find($productId);

        $order->removeOrderProduct($orderProduct);

        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'success' => true,
            'data' => [],
        ]);
    }

    /**
     * @Route("/get-products-by-order", name="add_products_by_order")
     */
    public function getProductsOfOrder(Request $request, OrderRepository $orderRepository)
    {
        $orderId = intval($request->request->get('orderId'));

        $order = $orderRepository->find($orderId);
        $orderProducts = $order->getOrderProducts()->getValues();
        $data = [];
        /** @var OrderProduct $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            $product = $orderProduct->getProduct();
            $data[] = [
                'id' => $orderProduct->getId(),
                'category' => [
                    'id' => $product->getCategory()->getId(),
                    'title' => $product->getCategory()->getTitle(),
                ],
                'title' => sprintf(
                    '#%s %s / P: %s$ / Q: %s',
                    $product->getId(),
                    $product->getTitle(),
                    $product->getPrice(),
                    $product->getQuantity()
                ),
                'pricePerOne' => $orderProduct->getPricePerOne(),
                'quantity' => $orderProduct->getQuantity(),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $data,
        ]);
    }
}
