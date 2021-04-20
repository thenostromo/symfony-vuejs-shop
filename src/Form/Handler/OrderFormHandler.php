<?php

namespace App\Form\Handler;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\DTO\CategoryEditModel;
use App\Form\DTO\OrderEditModel;
use App\Utils\Manager\CategoryManager;
use App\Utils\Manager\OrderManager;

class OrderFormHandler
{
    /**
     * @var OrderManager
     */
    public $orderManager;

    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @param OrderEditModel $orderEditModel
     */
    public function processOrderEditForm(OrderEditModel $orderEditModel)
    {
        $order = new Order();

        if ($orderEditModel->id) {
            $order = $this->orderManager->findOrder($orderEditModel->id);
        }

        $totalPrice = 0;
        $totalPriceWithDiscount = 0;

        $order->setOwner($orderEditModel->owner);
        $order->setStatus($orderEditModel->status);
        $order->setPromoCode($orderEditModel->promoCode);
        $order->setTotalPrice($totalPrice);

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

        $this->orderManager->save($order);

        return $order;
    }
}
