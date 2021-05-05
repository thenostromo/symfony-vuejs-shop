<?php

namespace App\EventSubscriber;

use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreatedFromCartNotificationSubscriber implements EventSubscriberInterface
{
    private $mailerSender;

    public function __construct(MailerSender $mailerSender)
    {
        $this->mailerSender = $mailerSender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreatedFromCartEvent::class => 'onOrderCreated',
        ];
    }

    public function onOrderCreated(OrderCreatedFromCartEvent $event): void
    {
        $this->sendEmailToClient($event);
        $this->sendEmailToManager($event);
    }

    public function sendEmailToClient(OrderCreatedFromCartEvent $event): void
    {
        $order = $event->getOrder();

        $mailerOptions = new MailerOptions();
        $mailerOptions->cc = 'manager@ranked-choice.com';
        $mailerOptions->recipient = $order->getOwner()->getEmail();
        $mailerOptions->subject = 'Ranked Choice Shop - Thank you for your purchase!';
        $mailerOptions->htmlTemplate = 'main/emails/thank_you_for_purchase.html.twig';
        $mailerOptions->context = [
            'order' => $order,
        ];

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }

    public function sendEmailToManager(OrderCreatedFromCartEvent $event): void
    {
        $order = $event->getOrder();

        $mailerOptions = new MailerOptions();
        $mailerOptions->cc = 'manager@ranked-choice.com';
        $mailerOptions->recipient = 'manager@ranked-choice.com';
        $mailerOptions->subject = 'Client created order';
        $mailerOptions->htmlTemplate = 'admin/emails/thank_you_for_purchase.html.twig';
        $mailerOptions->context = [
            'order' => $order,
        ];

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}
