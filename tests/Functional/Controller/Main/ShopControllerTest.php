<?php

namespace App\Tests\Functional\Controller\Main;

use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\Client;

class ShopControllerTest extends PantherTestCase
{
   /* public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/');

        $this->assertResponseIsSuccessful();
    }*/
    public function testIndex(): void
    {
     //   $client = static::createPantherClient();
       /* $chromeClient = static::createPantherClient(['browser' => static::CHROME]); // A majestic Panther
        $chromeClient->request('GET', '/en/login');
        $chromeClient->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);
        $this->assertSelectorWillContain('h1', 'Welcome, to your profile!');
        return;*/
        static::createPantherClient();

        $customSeleniumClient = Client::createSeleniumClient('http://127.0.0.1:4444/wd/hub', null, 'http://127.0.0.1:9080');
        static::startWebServer();
      //  $crawler = $client->request('GET', '/en/');

        $crawler = $customSeleniumClient->request('GET', '/en/login');
        $crawler = $customSeleniumClient->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);

        $this->assertSame($crawler->filter('#page_header_title')->text(), 'Welcome, to your profile!');
     //   $this->assertSelectorWillContain('.page_header_title', 'Welcome, to your profile!');

    }
}
