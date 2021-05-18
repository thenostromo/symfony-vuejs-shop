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

class CategoryFixtures extends Fixture
{
    public function getDefaultCategoriesData(): ?\Generator
    {
        yield ['jacket'];
        yield ['hat'];
        yield ['jeans'];
        yield ['dress'];
        yield ['sneakers'];
    }

    public function load(ObjectManager $manager)
    {
        $categoryGenerator = $this->getDefaultCategoriesData();
        foreach ($categoryGenerator as $categoryTitle) {
            $category = new Category();
            $category->setTitle($categoryTitle);
            $manager->persist($category);

            $this->addReference('category-'.$categoryTitle, $category);
        }

        $manager->flush();
    }
}
