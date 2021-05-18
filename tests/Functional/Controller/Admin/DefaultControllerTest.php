<?php

namespace App\Tests\Functional\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider getPublicUrls
     * @param string $url
     */
    public function testPublicUrls(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful(sprintf('The %s public URL loads correctly.', $url));
    }

    /**
     * @dataProvider getSecureUrls
     * @param string $url
     */
    public function testSecureUrls(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseRedirects(
            '/admin/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public function getPublicUrls(): ?\Generator
    {
        yield ['/admin/login'];
    }

    public function getSecureUrls(): ?\Generator
    {
        yield ['/admin/dashboard'];
        yield ['/admin/order/list'];
        yield ['/admin/order/add'];
        yield ['/admin/user/list'];
        yield ['/admin/user/add'];
    }
}
