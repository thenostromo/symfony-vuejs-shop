<?php

namespace App\Utils\Mailer;

use App\Utils\Mailer\DTO\MailerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerSender
{
    /**
     * @var MailerInterface
     */
    public $mailer;

    /**
     * @var LoggerInterface
     */
    public $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @param MailerOptions $mailerOptions
     */
    public function sendTemplatedEmail(MailerOptions $mailerOptions): void
    {
        $email = (new TemplatedEmail())
            ->to($mailerOptions->recipient)
            ->cc($mailerOptions->cc)
            ->subject($mailerOptions->subject)
            ->htmlTemplate($mailerOptions->htmlTemplate)
            ->context($mailerOptions->context);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical($mailerOptions->subject, [
                'errorText' => $e->getTraceAsString(),
            ]);

            $systemMailerOptions = new MailerOptions();
            $systemMailerOptions->text = $e->getTraceAsString();

            $this->sendSystemEmail($systemMailerOptions);
        }
    }

    /**
     * @param MailerOptions $mailerOptions
     */
    public function sendSystemEmail(MailerOptions $mailerOptions): void
    {
        $mailerOptions->subject = '[Exception] An error occurred while sending the letter';
        $mailerOptions->recipient = 'admin@ranked-choice.com';

        $email = (new Email())
            ->to($mailerOptions->recipient)
            ->subject($mailerOptions->subject)
            ->text($mailerOptions->text);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical($mailerOptions->subject, [
                'errorText' => $e->getTraceAsString(),
            ]);
        }
    }
}
