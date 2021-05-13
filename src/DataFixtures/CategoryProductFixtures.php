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

class CategoryProductFixtures extends Fixture
{
    /**
     * @var string
     */
    private $externalDefaultDir;

    /**
     * @var JsonExtractor
     */
    private $jsonExtractor;

    public function __construct(JsonExtractor $jsonExtractor, string $externalDir)
    {
        $this->jsonExtractor = $jsonExtractor;
        $this->externalDefaultDir = sprintf('%s%s', $externalDir, 'default');
    }

    public function load(ObjectManager $manager)
    {
        $categoryJackets = new Category();
        $categoryJackets->setTitle('Jacket');
        $manager->persist($categoryJackets);

        $categoryHats = new Category();
        $categoryHats->setTitle('Hat');
        $manager->persist($categoryHats);

        $categoryJeans = new Category();
        $categoryJeans->setTitle('Jeans');
        $manager->persist($categoryJeans);

        $categoryDresses = new Category();
        $categoryDresses->setTitle('Dress');
        $manager->persist($categoryDresses);

        $categorySneakers = new Category();
        $categorySneakers->setTitle('Sneakers');
        $manager->persist($categorySneakers);

        $fileName = 'products_data.json';
        $productsRaw = null;

        try {
            $productsRaw = $this->jsonExtractor->getFormattedContent($fileName, $this->externalDefaultDir);
        } catch (FileNotFoundException $ex) {
            throw new \Exception(sprintf('File not found: %s', $fileName));
        }

        if (!array_key_exists('products', $productsRaw)) {
            throw new \Exception(sprintf('Key \'product\' not found in the file: %s', $fileName));
        }

        foreach ($productsRaw['products'] as $productRaw) {
            $product = new Product();
            $product->setTitle($productRaw['title']);
            $product->setPrice($productRaw['price']);

            switch ($productRaw['category']) {
                case 'jackets':
                    $product->setCategory($categoryJackets);
                    break;
                case 'hats':
                    $product->setCategory($categoryHats);
                    break;
                case 'jeans':
                    $product->setCategory($categoryJeans);
                    break;
                case 'dresses':
                    $product->setCategory($categoryDresses);
                    break;
                case 'sneakers':
                    $product->setCategory($categorySneakers);
                    break;
            }

            $product->setQuantity($productRaw['quantity']);
            $product->setDescription($productRaw['description']);
            $product->setSize($productRaw['size']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
