<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Exception\FileNotFoundException;
use App\Utils\Extractor\JsonExtractor;
use App\Utils\FileSystem\FileSystemWorker;
use App\Utils\Manager\ProductManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    const COUNT_PRODUCTS_IN_CATEGORY = 5;

    public function load(ObjectManager $manager)
    {
        $categories = $manager->getRepository(Category::class)->findAll();

        foreach ($categories as $category) {

        }
    }
}
