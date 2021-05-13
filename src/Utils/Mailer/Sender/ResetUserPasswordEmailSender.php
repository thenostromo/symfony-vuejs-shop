<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\User;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

class ResetUserPasswordEmailSender
{
    private $mailerSender;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(MailerSender $mailerSender, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailerSender = $mailerSender;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param User   $user
     * @param ResetPasswordToken $resetToken
     *
     * @return TemplatedEmail
     */
    public function sendEmailToClient(User $user, ResetPasswordToken $resetToken): TemplatedEmail
    {
        $emailContext = [];
        $emailContext['resetToken'] = $resetToken;
        $emailContext['user'] = $user;
        $emailContext['homepageUrl'] = $this->urlGenerator->generate('shop_index', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $mailerOptions = (new MailerOptions())
            ->setRecipient($user->getEmail())
            ->setSubject('Ranked Choice Shop - Your password reset request')
            ->setHtmlTemplate('main/email/security/reset_password.html.twig')
            ->setContext($emailContext);

        return $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}
