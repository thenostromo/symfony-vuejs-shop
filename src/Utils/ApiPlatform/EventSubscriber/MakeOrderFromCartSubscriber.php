<?php

namespace App\Utils\ApiPlatform\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Order;
use App\Entity\User;
use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Manager\OrderManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class MakeOrderFromCartSubscriber implements EventSubscriberInterface
{
    private $security;

    private $orderManager;

    private $eventDispatcher;

    public function __construct(Security $security, OrderManager $orderManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->security = $security;
        $this->orderManager = $orderManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function makeOrder(ViewEvent $event)
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $contentJSON = $event->getRequest()->getContent();
        if (!$contentJSON) {
            return;
        }

        $content = json_decode($contentJSON, true);
        if (!array_key_exists('cartId', $content) || !array_key_exists('promoCodeId', $content)) {
            return;
        }

        $cartId = (int) $content['cartId'];
        $promoCodeId = (int) $content['promoCodeId'];

        $order->setOwner($user);

        $this->orderManager->addOrderProductsByCartId($order, $cartId);
        $this->orderManager->addPromoCodeByPromoCodeId($order, $promoCodeId);

        $this->orderManager->recalculateOrderTotalPrice($order);
    }

    public function sendNotificationsAboutOrder(ViewEvent $event): void
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }

        $this->eventDispatcher->dispatch(new OrderCreatedFromCartEvent($order));
    }

    /**
     * @return array|array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                [
                    'makeOrder', EventPriorities::PRE_WRITE,
                ],
                [
                    'sendNotificationsAboutOrder', EventPriorities::POST_WRITE,
                ],
            ],
        ];
    }
}
