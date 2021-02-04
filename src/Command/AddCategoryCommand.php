<?php

namespace App\Command;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class AddCategoryCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:add-category';

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

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates category and stores it in the database')
            ->addArgument('title', InputArgument::OPTIONAL, 'The title of the new user')
            ->addArgument('slug', InputArgument::OPTIONAL, 'The slug of the new user')
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
        if ($input->getArgument('title') && $input->getArgument('slug')) {
            return;
        }

        $this->io->title('Add Category Command Interactive Wizard');
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

        $slug = $input->getArgument('slug');
        if ($slug) {
            $this->io->text(' > <info>Slug</info>: ' . $slug);
        } else {
            $slug = $this->io->ask('Slug', null);
            $input->setArgument('slug', $slug);
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
        $stopwatch->start('add-category');

        $title = $input->getArgument('title');
        $slug = mb_strtolower($input->getArgument('slug'));

        $category = new Category();
        $category->setTitle($title);
        $category->setSlug($slug);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $this->io->success(sprintf('Category was successfully created: %s', $category->getTitle()));

        $event = $stopwatch->stop('add-category');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New category database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $category->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }
}
