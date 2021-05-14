<?php

namespace App\Tests\Functional\ApiPlatform;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

class ResourceTestUtils extends WebTestCase
{
    /**
     * @var string
     */
    protected $uriKey = '';

    // to see installed headers in AbstractBrowser: dd($client->server);
    public const REQUEST_HEADERS = [
        'HTTP_ACCEPT' => 'application/ld+json',
        'CONTENT_TYPE' => 'application/json',
    ];

    protected function checkDefaultUserHasNotAccess(AbstractBrowser $client, string $uri, string $method): void
    {
        $user = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);

        $client->loginUser($user, 'user_secured_area');

        $client->request($method, $uri, [], [], self::REQUEST_HEADERS, json_encode([]));
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
