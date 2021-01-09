<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(Request $request): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/make-order", name="cart_make_order")
     */
    public function makeOrder(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse([
                'status' => 0,
                'message' => 'You are not authorized'
            ]);
        }
        $cartJson = $request->request->get('cart');
        if (!$cartJson) {
            return new JsonResponse([
                'status' => 0,
                'message' => 'You cart is empty, check your order again, please'
            ]);
        }

        $cart = json_decode($cartJson, true);
        if (count($cart) > 0) {
            $order = new Order();
            $order->setOwner($this->getUser());
            $productsQuantity = [];
            $totalPrice = 0;

            foreach ($cart as $cartItem) {
                $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($cartItem['id']);
                $productsQuantity[$cartItem['id']] = $cartItem['quantity'];
                $totalPrice += $cartItem['quantity'] * $product->getPrice();
            }

            $order->setQuntity(json_encode($productsQuantity));
            $order->setTotalPrice($totalPrice);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
        }

        return new JsonResponse([
            'status' => 1,
            'message' => 'Thank you for your purchase!'
        ]);
    }
}
