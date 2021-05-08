<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\Order;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class OrderCreatedFromCartEmailSender
{
    private $mailerSender;

    public function __construct(MailerSender $mailerSender)
    {
        $this->mailerSender = $mailerSender;
    }

    /**
     * @param Order $order
     *
     * @return TemplatedEmail
     */
    public function sendEmailToClient(Order $order): TemplatedEmail
    {
        $mailerOptions = (new MailerOptions())
            ->setRecipient($order->getOwner()->getEmail())
            ->setCc('manager@ranked-choice.com')
            ->setSubject('Ranked Choice Shop - Thank you for your purchase!')
            ->setHtmlTemplate('main/emails/thank_you_for_purchase.html.twig')
            ->setContext([
                'order' => $order,
            ]);

        return $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }

    /**
     * @param Order $order
     *
     * @return TemplatedEmail
     */
    public function sendEmailToManager(Order $order): TemplatedEmail
    {
        $mailerOptions = (new MailerOptions())
            ->setRecipient('manager@ranked-choice.com')
            ->setSubject('Client created order')
            ->setHtmlTemplate('admin/emails/thank_you_for_purchase.html.twig')
            ->setContext([
                'order' => $order,
            ]);

        return $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}
