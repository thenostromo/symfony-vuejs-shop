<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Exception\FileNotFoundException;
use App\Utils\Extractor\JsonExtractor;
use App\Utils\Product\ProductManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryProductFixtures extends Fixture
{
    /**
     * @var string
     */
    private $externalDefaultDir;

    /**
     * @var ProductManager
     */
    private $productManager;

    /**
     * @var JsonExtractor
     */
    private $jsonExtractor;

    public function __construct(JsonExtractor $jsonExtractor, ProductManager $productManager, string $externalDir)
    {
        $this->jsonExtractor = $jsonExtractor;
        $this->productManager = $productManager;
        $this->externalDefaultDir = sprintf('%s%s', $externalDir, 'default');
    }

    public function load(ObjectManager $manager)
    {
        $categoryJackets = new Category();
        $categoryJackets->setTitle('Jacket');
        $categoryJackets->setTitlePlural('Jackets');
        $categoryJackets->setSlug('jackets');
        $manager->persist($categoryJackets);

        $categoryHats = new Category();
        $categoryHats->setTitle('Hat');
        $categoryHats->setTitlePlural('Hats');
        $categoryHats->setSlug('hats');
        $manager->persist($categoryHats);

        $categoryJeans = new Category();
        $categoryJeans->setTitle('Jeans');
        $categoryJeans->setTitlePlural('Jeans');
        $categoryJeans->setSlug('jeans');
        $manager->persist($categoryJeans);

        $categoryDresses = new Category();
        $categoryDresses->setTitle('Dress');
        $categoryDresses->setTitlePlural('Dresses');
        $categoryDresses->setSlug('dresses');
        $manager->persist($categoryDresses);

        $categorySneakers = new Category();
        $categorySneakers->setTitle('Sneakers');
        $categorySneakers->setTitlePlural('Sneakers');
        $categorySneakers->setSlug('sneakers');
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

            $product = $this->productManager->updateProductImages($product, $productRaw['cover']);
        }

        $manager->flush();
    }
}
