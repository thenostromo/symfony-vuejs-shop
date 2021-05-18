<?php

namespace App\Tests\Functional\Controller\Main;

use App\DataFixtures\UserFixtures;
use App\Tests\Functional\SymfonyPanther\BasePantherTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class AuthLoginControllerTest extends BasePantherTestCase
{
    /**
     * Default testing.
     */
    public function testLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/en/login');
        $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);

        $this->assertResponseRedirects('/en/profile', Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }

    /**
     * For testing UI in browser (real-time).
     */
    public function testLoginWithSeleniumClient(): void
    {
        $client = $this->initSeleniumClient();

        $client->request('GET', '/en/login');

        $crawler = $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);

        sleep(3);

        $this->takeScreenshot($client, 'auth-login-controller-test-login__1');
        $this->assertSame(
            $crawler->filter('#page_header_title')->text(),
            'Welcome, to your profile!'
        );
    }

    /**
     * For testing UI in browser (browser hide).
     */
    public function testLoginWithPantherClient(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);

        $client->request('GET', '/en/login');

        $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);

        $this->assertSame(self::$baseUri.'/en/profile', $client->getCurrentURL());

        $this->assertPageTitleContains('My Profile - RankedChoice');
        $this->assertSelectorTextContains('#page_header_title', 'Welcome, to your profile!');
    }
}
