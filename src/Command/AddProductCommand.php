<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class AddProductCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:add-product';

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates product and stores it in the database')
            ->addArgument('title', InputArgument::OPTIONAL, 'The title of the new user')
            ->addArgument('price', InputArgument::OPTIONAL, 'The price of the new user')
            ->addArgument('categoryId', InputArgument::OPTIONAL, 'The category of the new user')
            ->addArgument('quantity', InputArgument::OPTIONAL, 'The quantity of the new user')
            ->addArgument('cover', InputArgument::OPTIONAL, 'The quantity of the new user')
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
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('title') && $input->getArgument('price') && $input->getArgument('categoryId') && $input->getArgument('quantity') !== null) {
            return;
        }

        $this->io->title('Add Product Command Interactive Wizard');
        $this->io->text([
            'Please, enter some information',
        ]);

        $title = $input->getArgument('title');
        if ($title) {
            $this->io->text(' > <info>Title</info>: ' . $title);
        } else {
            $title = $this->io->ask('Title', null);
            $input->setArgument('title', $title);
        }

        $price = $input->getArgument('price');
        if ($price) {
            $this->io->text(' > <info>Price</info>: ' . $price);
        } else {
            $price = $this->io->ask('price', null);
            $input->setArgument('price', $price);
        }

        $categoryId = $input->getArgument('categoryId');
        if ($categoryId) {
            $this->io->text(' > <info>Category Id</info>: ' . $categoryId);
        } else {
            $categoryId = $this->io->ask('categoryId', null);
            $input->setArgument('categoryId', $categoryId);
        }

        $quantity = $input->getArgument('quantity');
        if ($quantity !== null) {
            $this->io->text(' > <info>Quantity</info>: ' . $quantity);
        } else {
            $quantity = $this->io->ask('quantity', 0);
            $input->setArgument('quantity', $quantity);
        }

        $cover = $input->getArgument('cover');
        if ($cover) {
            $this->io->text(' > <info>cover</info>: ' . $cover);
        } else {
            $cover = $this->io->ask('cover', null);
            $input->setArgument('cover', $cover);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-product');

        $title = $input->getArgument('title');
        $price = floatval($input->getArgument('price'));
        $categoryId = intval($input->getArgument('categoryId'));
        $quantity = intval($input->getArgument('quantity'));
        $cover = $input->getArgument('cover');

        $category = $this->categoryRepository->find($categoryId);

        $product = new Product();
        $product->setTitle($title);
        $product->setPrice($price);
        $product->setCategory($category);
        $product->setQuantity($quantity);
        $product->setCover($cover);
        $product->setActive(true);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $this->io->success(sprintf('Product was successfully created: %s', $product->getTitle()));

        $event = $stopwatch->stop('add-product');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New product database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $category->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }
}
