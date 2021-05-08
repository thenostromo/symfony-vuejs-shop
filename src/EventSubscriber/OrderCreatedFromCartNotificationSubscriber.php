<?php

namespace App\EventSubscriber;

use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use App\Utils\Mailer\Sender\OrderCreatedFromCartEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreatedFromCartNotificationSubscriber implements EventSubscriberInterface
{
    private $orderCreatedFromCartEmailSender;

    public function __construct(OrderCreatedFromCartEmailSender $orderCreatedFromCartEmailSender)
    {
        $this->orderCreatedFromCartEmailSender = $orderCreatedFromCartEmailSender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreatedFromCartEvent::class => 'onOrderCreated',
        ];
    }

    public function onOrderCreated(OrderCreatedFromCartEvent $event): void
    {
        $order = $event->getOrder();

        $this->orderCreatedFromCartEmailSender->sendEmailToClient($order);
        $this->orderCreatedFromCartEmailSender->sendEmailToManager($order);
    }
}
