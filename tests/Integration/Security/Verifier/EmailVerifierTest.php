<?php

namespace App\Tests\Integration\Security\Verifier;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Verifier\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

/**
 * @group integration
 */
class EmailVerifierTest extends KernelTestCase
{
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        /* @var EmailVerifier $emailVerifier */
        $this->emailVerifier = self::$container->get(EmailVerifier::class);
        $this->userRepository = self::$container->get(UserRepository::class);
    }

    public function testGenerateEmailSignatureAndHandleEmailConfirmation()
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);
        $user->setIsVerified(false);

        $emailSignature = $this->checkGenerateEmailSignature($user);

        $this->checkHandleEmailConfirmation($emailSignature, $user);
    }

    private function checkGenerateEmailSignature(User $user): VerifyEmailSignatureComponents
    {
        $currentDateTime = new \DateTimeImmutable();
        $emailSignature = $this->emailVerifier->generateEmailSignature('app_verify_email', $user);

        $this->assertGreaterThan($currentDateTime, $emailSignature->getExpiresAt());

        return $emailSignature;
    }

    private function checkHandleEmailConfirmation(VerifyEmailSignatureComponents $emailSignature, User $user): void
    {
        $this->assertFalse($user->isVerified());
        $this->emailVerifier->handleEmailConfirmation($emailSignature->getSignedUrl(), $user);

        $this->assertTrue($user->isVerified());
    }
}
