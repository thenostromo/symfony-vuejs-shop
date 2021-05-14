<?php

namespace App\Tests\Functional\Controller\Admin;

use App\DataFixtures\UserFixtures;
use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = $this->initAdminUser();

        $client->request('GET', '/admin/order/list');

        $this->assertResponseIsSuccessful();

        $rowsCount = $client->getCrawler()->filter('#main_table > tbody > tr')->count();
        $this->assertEquals(2, $rowsCount);
    }

    public function testIndexFilterIdWorked(): void
    {
        $client = $this->initAdminUser();

        $client->request('GET', '/admin/order/list');

        $this->assertResponseIsSuccessful();

        $order = self::$container->get(OrderRepository::class)->findOneBy([]);

        $client->submitForm('Apply filters', [
            'order_filter_form1[id]' => $order->getId(),
        ], 'GET');

        $this->assertResponseIsSuccessful();

        $crawler = $client->getCrawler();

        $rowsCount = $crawler->filter('#main_table > tbody > tr')->count();
        $this->assertEquals(1, $rowsCount);
    }

    public function testAdd(): void
    {
        $client = $this->initAdminUser();

        $client->request('GET', '/admin/order/add');

        $this->assertResponseIsSuccessful();

        $userClient = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);
        $ordersTotalCount = self::$container->get(OrderRepository::class)->getCountActiveOrders();

        $client->submitForm('Save changes', [
            'order_filter[owner]' => $userClient->getId(),
            'order_filter[status]' => Order::STATUS_CREATED,
        ], 'POST');

        $ordersNewCount = self::$container->get(OrderRepository::class)->getCountActiveOrders();

        $this->assertGreaterThan($ordersTotalCount, $ordersNewCount);

        $lastOrder = self::$container->get(OrderRepository::class)->findOneBy([], ['id' => 'DESC']);

        $this->assertResponseRedirects('/admin/order/edit/'.$lastOrder->getId(), Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains('div', 'Your changes were saved!');

        $this->assertResponseIsSuccessful();
    }

    public function testDelete(): void
    {
        $client = $this->initAdminUser();

        /** @var Order $lastOrder */
        $lastOrder = self::$container->get(OrderRepository::class)->findOneBy([], ['id' => 'DESC']);
        $lastOrderId = $lastOrder->getId();

        $this->assertSame(false, $lastOrder->getIsDeleted());

        $client->request('GET', '/admin/order/delete/'.$lastOrderId);

        $this->assertResponseRedirects('/admin/order/list', Response::HTTP_FOUND);

        $client->followRedirect();

        /** @var Order $lastOrder */
        $lastOrderChanged = self::$container->get(OrderRepository::class)->find($lastOrderId);

        $this->assertSame(true, $lastOrderChanged->getIsDeleted());
    }

    private function initAdminUser(): AbstractBrowser
    {
        $client = static::createClient();

        $user = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user, 'admin_secured_area');

        return $client;
    }
}
