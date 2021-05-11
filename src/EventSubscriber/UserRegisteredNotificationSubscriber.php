<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredNotificationSubscriber implements EventSubscriberInterface
{
    private $emailSender;
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    public function __construct(UserRegisteredEmailSender $emailSender, EmailVerifier $emailVerifier)
    {
        $this->emailSender = $emailSender;
        $this->emailVerifier = $emailVerifier;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();

        $emailSignature = $this->emailVerifier->generateEmailSignature('app_verify_email', $user);

        $this->emailSender->sendEmailToClient($user, $emailSignature);
    }
}
