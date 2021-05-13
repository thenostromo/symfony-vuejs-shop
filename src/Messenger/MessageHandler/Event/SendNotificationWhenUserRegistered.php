<?php

namespace App\Messenger\MessageHandler\Event;

use App\Messenger\Message\Event\UserRegisteredEvent;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendNotificationWhenUserRegistered implements MessageHandlerInterface
{
    private $emailSender;

    /**
     * @var EmailVerifier
     */
    private $emailVerifier;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserRegisteredEmailSender $emailSender, EmailVerifier $emailVerifier, UserManager $userManager)
    {
        $this->emailSender = $emailSender;
        $this->emailVerifier = $emailVerifier;
        $this->userManager = $userManager;
    }

    public function __invoke(UserRegisteredEvent $event): void
    {
        $userId = $event->getUserId();
        $user = $this->userManager->find($userId);

        if (!$user) {
            return;
        }

        $emailSignature = $this->emailVerifier->generateEmailSignature('app_verify_email', $user);

        $this->emailSender->sendEmailToClient($user, $emailSignature);
    }
}
