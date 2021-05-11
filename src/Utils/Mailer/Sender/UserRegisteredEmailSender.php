<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\User;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

class UserRegisteredEmailSender
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
     * @param User                           $user
     * @param VerifyEmailSignatureComponents $signatureComponents
     *
     * @return TemplatedEmail
     */
    public function sendEmailToClient(User $user, VerifyEmailSignatureComponents $signatureComponents): TemplatedEmail
    {
        $emailContext = [];
        $emailContext['signedUrl'] = $signatureComponents->getSignedUrl();
        $emailContext['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $emailContext['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $emailContext['user'] = $user;
        $emailContext['homepageUrl'] = $this->urlGenerator->generate('shop_index', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $mailerOptions = (new MailerOptions())
            ->setRecipient($user->getEmail())
            ->setSubject('Ranked Choice Shop - Please Confirm your Email!')
            ->setHtmlTemplate('main/email/security/confirmation_email.html.twig')
            ->setContext($emailContext);

        return $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}
