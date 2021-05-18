<?php

namespace App\Security\Verifier;

use App\Utils\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private $verifyEmailHelper;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(VerifyEmailHelperInterface $helper, UserManager $userManager)
    {
        $this->verifyEmailHelper = $helper;
        $this->userManager = $userManager;
    }

    public function generateEmailSignature(string $verifyEmailRouteName, UserInterface $user): VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );
    }

    /**
     * @param string $requestUri
     * @param UserInterface $user
     */
    public function handleEmailConfirmation(string $requestUri, UserInterface $user): void
    {
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($requestUri, $user->getId(), $user->getEmail());

            $user->setIsVerified(true);

            $this->userManager->save($user);
        } catch (VerifyEmailExceptionInterface $e) {

        }
    }
}
