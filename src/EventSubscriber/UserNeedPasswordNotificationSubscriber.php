<?php

namespace App\EventSubscriber;

use App\Event\UserNeedPasswordEvent;
use App\Event\UserRegisteredEvent;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Mailer\Sender\UserNeedPasswordEmailSender;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserNeedPasswordNotificationSubscriber implements EventSubscriberInterface
{
    private $emailSender;

    public function __construct(UserNeedPasswordEmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserNeedPasswordEvent::class => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserNeedPasswordEvent $event): void
    {
        $user = $event->getUser();
        $plainPassword = $event->getPlainPassword();

        $this->emailSender->sendEmailToClient($user, $plainPassword);
    }
}
