<?php

namespace App\Tests\Integration\Utils\Mailer\Sender;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Mailer\Sender\UserNeedPasswordEmailSender;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mime\Address;

/**
 * @group integration
 */
class UserNeedPasswordEmailSenderTest extends KernelTestCase
{
    public function testSendEmailToClient(): void
    {
        self::bootKernel();

        $container = self::$container;

        /** @var User $user */
        $user = $container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);
        $plainPasswordCreated = 'pass123';

        /** @var UserNeedPasswordEmailSender $userNeedPasswordEmailSender */
        $userNeedPasswordEmailSender = $container->get(UserNeedPasswordEmailSender::class);
        $templatedEmail = $userNeedPasswordEmailSender->sendEmailToClient($user, $plainPasswordCreated);

        $this->assertSame('Ranked Choice Shop - Your new password', $templatedEmail->getSubject());
        $this->assertCount(1, $templatedEmail->getTo());

        $addressTo = $templatedEmail->getTo()[0];
        $this->assertInstanceOf(Address::class, $addressTo);
        $this->assertSame($user->getEmail(), $addressTo->getAddress());

        $this->assertCount(3, $templatedEmail->getContext());
        $plainPasswordSent = $templatedEmail->getContext()['plainPassword'];
        $this->assertSame($plainPasswordCreated, $plainPasswordSent);
    }
}
