<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\FileNotFoundException;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Utils\Extractor\JsonExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * НУЖНО УБРАТЬ ПЕРЕГРУЖЕННОСТЬ В КОНСТРУКТОРЕ, ВЫДЕЛИТЬ КЛАСС ДЛЯ СЧЕТЧИКА
 *
 * Class ImportDefaultProductsCommand
 * @package App\Command
 */
class ImportDefaultUsersCommand extends Command
{
    protected static $defaultName = 'app:import-default-users';

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var string
     */
    private $externalDefaultDir;

    /**
     * @var JsonExtractor
     */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
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
        $stopwatch->start('import-default-users');


        $user = new User();
        $user->setEmail('admin_1@ranked-choice.com');
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, 'admin123')
        );
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('Users was successfully created'));

        $event = $stopwatch->stop('import-default-users');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New products database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $category->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }
}
