<?php

namespace App\Utils\ApiPlatform\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cart;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class SetSessionIdOnCartSubscriber implements EventSubscriberInterface
{
    public function setSessionId(ViewEvent $event)
    {
        $cart = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$cart instanceof Cart || Request::METHOD_POST !== $method) {
            return;
        }

        $phpSessId = $event->getRequest()->cookies->get("PHPSESSID");

        if (!$phpSessId) {
            return;
        }

        $cart->setSessionId($phpSessId);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setSessionId', EventPriorities::PRE_WRITE],
        ];
    }
}
