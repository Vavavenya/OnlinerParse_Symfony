<?php

namespace App\Command;

use App\Event\BeforePrintChangesEvent;
use App\Services\CategoryService;
use App\Services\ChangeSetService;
use App\Services\ProductService;
use App\Services\JsonService;
use App\Services\ParserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ParseProductsCommand extends Command
{
    protected static $defaultName = 'onliner:parse';

    public ParserService $parserService;
    public JsonService $jsonService;
    public ProductService $productService;
    public CategoryService $categoryService;
    public LoggerInterface $logger;

    public function __construct(
        ParserService   $parserService,
        JsonService     $jsonService,
        ProductService  $productService,
        CategoryService $categoryService,
        LoggerInterface $logger,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->parserService   = $parserService;
        $this->jsonService     = $jsonService;
        $this->productService  = $productService;
        $this->categoryService = $categoryService;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
                'category',
                null,
                InputOption::VALUE_REQUIRED,
                'Which category of catalog you want to parse?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $categoryName = $input->getOption('category');

        $output->writeln(['==START==', '.Get json']);

        $json = $this->parserService->parseAllProducts($categoryName);

        $output->writeln('..Configure json');

        $category = $this->categoryService->getCategory($categoryName);
        $products = $this->jsonService->getData($json);

        $output->writeln('...Insert in Database');
        $this->productService->insertRecords($products, $category);
        $this->printInfoAboutChanges(new SymfonyStyle($input, $output));

        $output->writeln('==OK==');

        return Command::SUCCESS;
    }

    private function printInfoAboutChanges(SymfonyStyle $symfonyStyle): void
    {
        $changeList = ChangeSetService::getChangeList();
        $tableBody  = [];

        foreach ($changeList as $action => $classes) {
            foreach ($classes as $className => $amountChanges) {
                $tableBody[] = [$action, $className, $amountChanges];
            }
        }

        $event = new BeforePrintChangesEvent($tableBody);
        $this->dispatcher->dispatch($event,BeforePrintChangesEvent::NAME);

        $symfonyStyle->table(
            ['Type', 'Class name', 'amount'],
            $event->getChanges()
        );
    }
}