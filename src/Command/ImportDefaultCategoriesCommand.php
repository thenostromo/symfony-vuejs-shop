<?php

namespace App\Command;

use App\Entity\Category;
use App\Exception\FileNotFoundException;
use App\Repository\CategoryRepository;
use App\Utils\Extractor\CsvExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class ImportDefaultCategoriesCommand extends Command
{
    protected static $defaultName = 'app:import-default-categories';

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
     * @var CsvExtractor
     */
    private $csvExtractor;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository, CsvExtractor $csvExtractor, string $externalDir)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->categoryRepository = $categoryRepository;
        $this->csvExtractor = $csvExtractor;
        $this->externalDefaultDir = sprintf('%s%s', $externalDir, 'default');

        $this->counters = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Import default categories to the database')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('import-default-categories');

        $fileName = 'categories_data.csv';
        $categoriesRaw = null;

        try {
            $categoriesRaw = $this->csvExtractor->getFormattedContent($fileName, $this->externalDefaultDir);
        } catch (FileNotFoundException $ex) {
            $this->io->error(sprintf('File not found: %s', $fileName));

            return Command::FAILURE;
        }

        foreach ($categoriesRaw as $categoryRaw) {
            if (count($categoryRaw) < 2) {
                continue;
            }

            $category = new Category();
            $category->setTitle($categoryRaw[0]);
            $category->setSlug($categoryRaw[1]);

            $this->entityManager->persist($category);
            $this->toggleCounter(self::COUNTER_IMPORTED_CATEGORIES_KEY);
        }

        $this->entityManager->flush();

        $this->io->success(sprintf('Categories was successfully created: %s', $this->getCounter(self::COUNTER_IMPORTED_CATEGORIES_KEY)));

        $event = $stopwatch->stop('import-default-categories');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New category database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $category->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

    const COUNTER_IMPORTED_CATEGORIES_KEY = 'imported_categories';

    /**
     * @var array
     */
    private $counters;

    /**
     * @param string $counter
     *
     * @return int
     */
    private function toggleCounter(string $counter): int
    {
        if (array_key_exists($counter, $this->counters)) {
            ++$this->counters[$counter];
        } else {
            $this->counters[$counter] = 1;
        }

        return $this->counters[$counter];
    }

    /**
     * @param string $counter
     *
     * @return int
     */
    private function getCounter(string $counter): int
    {
        return array_key_exists($counter, $this->counters)
            ? $this->counters[$counter]
            : 0;
    }
}
