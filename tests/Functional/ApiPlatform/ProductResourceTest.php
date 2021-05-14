<?php

namespace App\Tests\Functional\ApiPlatform;

use App\DataFixtures\UserFixtures;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;

class ProductResourceTest extends ResourceTestUtils
{
    /**
     * @var string
     */
    protected $uriKey = '/api/products';

    public function testCreateProduct()
    {
        $client = self::createClient();

        $this->checkDefaultUserHasNotAccess($client, $this->uriKey, 'POST');

        $user = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user, 'user_secured_area');

        $postData = [
            'title' => 'New Product',
            'price' => '100',
            'quantity' => 5,
        ];

        $client->request('POST', $this->uriKey, [], [], self::REQUEST_HEADERS, json_encode($postData));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testUpdateProduct()
    {
        $client = self::createClient();

        $product = self::$container->get(ProductRepository::class)->findOneBy([]);

        $uri = $this->uriKey.'/'.$product->getId();
        $this->checkDefaultUserHasNotAccess($client, $uri, 'PUT');

        $user = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user, 'user_secured_area');

        $postData = [
            'title' => 'Changed Product Title',
        ];

        $client->request('PUT', $uri, [], [], self::REQUEST_HEADERS, json_encode($postData));

        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetProducts()
    {
        $client = self::createClient();

        $client->request('GET', $this->uriKey, [], [], self::REQUEST_HEADERS);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetProduct()
    {
        $client = self::createClient();

        $product = self::$container->get(ProductRepository::class)->findOneBy([]);

        $uri = $this->uriKey.'/'.$product->getId();

        $client->request('GET', $uri, [], [], self::REQUEST_HEADERS);

        $this->assertResponseStatusCodeSame(200);
    }
}
