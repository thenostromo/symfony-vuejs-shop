<?php

namespace App\Messenger\MessageHandler\Command;

use App\Messenger\Message\Command\SendUserNewPassword;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Mailer\Sender\UserNeedPasswordEmailSender;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendUserNewPasswordHandler implements MessageHandlerInterface
{
    private $emailSender;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserNeedPasswordEmailSender $emailSender, UserManager $userManager)
    {
        $this->emailSender = $emailSender;
        $this->userManager = $userManager;
    }

    public function __invoke(SendUserNewPassword $event): void
    {
        $userId = $event->getUserId();
        $plainPassword = $event->getPlainPassword();
        $user = $this->userManager->find($userId);

        if (!$user) {
            return;
        }

        $this->emailSender->sendEmailToClient($user, $plainPassword);
    }
}
