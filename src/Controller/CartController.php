<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\PromoCode;
use App\Repository\PromoCodeRepository;
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
     * @Route("/approve-promo-code", name="cart_approve_promo_code")
     */
    public function approvePromoCode(Request $request, PromoCodeRepository $promoCodeRepository): Response
    {
        $promoCodeValue = $request->request->get('promo_code');

        /** @var PromoCode $promoCode */
        $promoCode = $promoCodeRepository->getValidPromoCodeByValue($promoCodeValue);

        if (!$promoCode) {
            return new JsonResponse([
                'success' => false,
                'data' => []
            ]);
        }

        $promoCodeModel = [];
        $promoCodeModel['discount'] = $promoCode->getDiscount();

        return new JsonResponse([
            'success' => true,
            'data' => $promoCodeModel
        ]);
    }

    /**
     * @Route("/make-order", name="cart_make_order")
     */
    public function makeOrder(Request $request, PromoCodeRepository $promoCodeRepository): Response
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
        $promoCodeValue = $request->request->get('promoCodeValue');

        if (count($cart) > 0) {
            $entityManager = $this->getDoctrine()->getManager();

            $order = new Order();
            $order->setOwner($this->getUser());
            $totalPrice = 0;

            foreach ($cart as $cartItem) {
                /** @var Product $product */
                $product = $entityManager->getRepository(Product::class)->findOneBy([
                    'id' => $cartItem['id'],
                    'isHidden' => false,
                    'isDeleted' => false
                ]);
                if (!$product) {
                    continue;
                }

                $orderProduct = new OrderProduct();
                $orderProduct->setQuantity($cartItem['quantity']);
                $orderProduct->setProduct($product);
                $orderProduct->setPricePerOne($product->getPrice());
                $orderProduct->setAppOrder($order);

                $entityManager->persist($orderProduct);
                $order->addOrderProduct($orderProduct);

                $totalPrice += $cartItem['quantity'] * $product->getPrice();
            }

            $order->setTotalPrice($totalPrice);

            $promoCode = $promoCodeRepository->getValidPromoCodeByValue($promoCodeValue);
            if ($promoCode) {
                $order->setPromoCode($promoCode);
            }

            $entityManager->persist($order);
            $entityManager->flush();
        }

        return new JsonResponse([
            'status' => 1,
            'message' => 'Thank you for your purchase!'
        ]);
    }
}
