<?php

namespace App\Messenger\MessageHandler\Command;

use App\Entity\User;
use App\Messenger\Message\Command\ResetUserPassword;
use App\Utils\Mailer\Sender\ResetUserPasswordEmailSender;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetUserPasswordHandler implements MessageHandlerInterface
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

    public function __invoke(ResetUserPassword $resetUserPassword): void
    {
        $email = $resetUserPassword->getEmail();
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
