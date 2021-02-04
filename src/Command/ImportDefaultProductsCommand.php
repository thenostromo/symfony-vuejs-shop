<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use App\Exception\FileNotFoundException;
use App\Repository\CategoryRepository;
use App\Utils\Extractor\JsonExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * НУЖНО УБРАТЬ ПЕРЕГРУЖЕННОСТЬ В КОНСТРУКТОРЕ, ВЫДЕЛИТЬ КЛАСС ДЛЯ СЧЕТЧИКА
 *
 * Class ImportDefaultProductsCommand
 * @package App\Command
 */
class ImportDefaultProductsCommand extends Command
{
    protected static $defaultName = 'app:import-default-products';

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var string
     */
    private $externalDefaultDir;

    /**
     * @var JsonExtractor
     */
    private $jsonExtractor;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository, JsonExtractor $jsonExtractor, string $externalDir)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->categoryRepository = $categoryRepository;
        $this->jsonExtractor = $jsonExtractor;
        $this->externalDefaultDir = sprintf('%s%s', $externalDir, 'default');

        $this->counters = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Import default products to the database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('import-default-products');

        $fileName = 'products_data.json';
        $productsRaw = null;

        try {
            $productsRaw = $this->jsonExtractor->getFormattedContent($fileName, $this->externalDefaultDir);
        } catch (FileNotFoundException $ex) {
            $this->io->error(sprintf('File not found: %s', $fileName));
            return Command::FAILURE;
        }

        if (!array_key_exists('products', $productsRaw)) {
            $this->io->error(sprintf('Key \'product\' not found in the file: %s', $fileName));
            return Command::FAILURE;
        }

        foreach ($productsRaw['products'] as $productRaw) {
            $category = $this->categoryRepository->find($productRaw['categoryId']);

            $product = new Product();
            $product->setTitle($productRaw['title']);
            $product->setPrice($productRaw['price']);
            $product->setCategory($category);
            $product->setQuantity($productRaw['quantity']);
            $product->setCover($productRaw['cover']);
            $product->setDescription($productRaw['description']);
            $product->setSize($productRaw['size']);
            $product->setActive(true);

            $this->entityManager->persist($product);
            $this->toggleCounter(self::COUNTER_IMPORTED_PRODUCTS_KEY);
        }

        $this->entityManager->flush();

        $this->io->success(sprintf('Products was successfully created: %s', $this->getCounter(self::COUNTER_IMPORTED_PRODUCTS_KEY)));

        $event = $stopwatch->stop('import-default-products');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New products database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $category->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

    CONST COUNTER_IMPORTED_PRODUCTS_KEY = 'imported_products';

    /**
     * @var array
     */
    private $counters;

    /**
     * @param string $counter
     * @return int
     */
    private function toggleCounter(string $counter): int
    {
        if (array_key_exists($counter, $this->counters)) {
            $this->counters[$counter]++;
        } else {
            $this->counters[$counter] = 1;
        }

        return $this->counters[$counter];
    }

    /**
     * @param string $counter
     * @return int
     */
    private function getCounter(string $counter): int
    {
        return array_key_exists($counter, $this->counters)
            ? $this->counters[$counter]
            : 0;
    }
}
