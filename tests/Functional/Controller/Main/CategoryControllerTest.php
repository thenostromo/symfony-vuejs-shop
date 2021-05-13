<?php

namespace App\Tests\Functional\Controller\Main;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
{
    public function testShow(): void
    {
        $client = static::createClient();

        $categorySlug = 'jeans';

        $category = self::$container->get(CategoryRepository::class)->findOneBy(['slug' => $categorySlug]);

        $this->assertNotNull($category);

        $uri = '/en/category/'.$category->getSlug();
        $crawler = $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains($category->getTitle().' - RankedChoice');
    }
}
