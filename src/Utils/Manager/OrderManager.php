<?php

namespace App\Utils\Manager;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\PromoCode;
use Doctrine\Persistence\ObjectRepository;

class OrderManager extends AbstractBaseManager
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    /**
     * @param Order  $order
     * @param string $cartId
     */
    public function addOrderProductsByCartId(Order $order, string $cartId): void
    {
        /** @var Cart $cart */
        $cart = $this->entityManager->getRepository(Cart::class)->find($cartId);

        if ($cart) {
            /** @var CartProduct $cartProduct */
            foreach ($cart->getCartProducts()->getValues() as $cartProduct) {
                $orderProduct = new OrderProduct();
                $orderProduct->setAppOrder($order);
                $orderProduct->setPricePerOne($cartProduct->getProduct()->getPrice());
                $orderProduct->setQuantity($cartProduct->getQuantity());
                $orderProduct->setProduct($cartProduct->getProduct());

                $order->addOrderProduct($orderProduct);
                $this->entityManager->persist($orderProduct);
            }
        }
    }

    /**
     * @param Order  $order
     * @param string $promoCodeId
     */
    public function addPromoCodeByPromoCodeId(Order $order, string $promoCodeId): void
    {
        /** @var PromoCode $promoCode */
        $promoCode = $this->entityManager->getRepository(PromoCode::class)->find($promoCodeId);

        if ($promoCode) {
            $order->setPromoCode($promoCode);
        }
    }

    /**
     * @param Order $order
     *
     * @return Order
     */
    public function recalculateOrderTotalPrice(Order $order): Order
    {
        $totalPrice = 0;
        $totalPriceWithDiscount = 0;

        $orderProducts = $order->getOrderProducts()->getValues();

        /** @var OrderProduct $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            $totalPrice += $orderProduct->getQuantity() * $orderProduct->getPricePerOne();
        }

        $promoCode = $order->getPromoCode();
        if ($promoCode) {
            $promoCodeDiscount = $promoCode->getDiscount();
            $totalPriceWithDiscount = $totalPrice - (($totalPrice / 100) * $promoCodeDiscount);
        }

        $order->setTotalPrice($totalPriceWithDiscount);

        return $order;
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity): void
    {
        $entity->setIsDeleted(true);
        $this->save($entity);
    }
}
