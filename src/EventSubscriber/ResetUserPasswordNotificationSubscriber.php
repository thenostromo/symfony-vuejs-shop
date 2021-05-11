<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\ResetUserPasswordEvent;
use App\Utils\Mailer\Sender\ResetUserPasswordEmailSender;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetUserPasswordNotificationSubscriber implements EventSubscriberInterface
{
    private $emailSender;
    /**
     * @var ResetPasswordHelperInterface
     */
    private $resetPasswordHelper;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(ResetUserPasswordEmailSender $emailSender, UserManager $userManager, ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->emailSender = $emailSender;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->userManager = $userManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ResetUserPasswordEvent::class => 'onUserResetPassword',
        ];
    }

    public function onUserResetPassword(ResetUserPasswordEvent $event): void
    {
        $email = $event->getEmail();
        $resetToken = null;

        /** @var User|null $user */
        $user = $this->userManager->getRepository()->findOneBy([
            'email' => $email,
        ]);

        try {
            if ($user) {
                $resetToken = $this->resetPasswordHelper->generateResetToken($user);
                $this->emailSender->sendEmailToClient($user, $resetToken);
            }
        } catch (ResetPasswordExceptionInterface $e) {
        }
    }
}
