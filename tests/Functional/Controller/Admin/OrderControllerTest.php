<?php

namespace App\Tests\Functional\Controller\Admin;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        $user = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user, 'admin_secured_area');

        $client->request('GET', '/admin/order/list');

        $this->assertResponseIsSuccessful();
    }
}
