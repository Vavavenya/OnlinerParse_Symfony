<?php

namespace App\Command;

use App\Services\CategoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CategoryListCommand extends Command
{
    protected static $defaultName = 'category:list';

    public CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle   = new SymfonyStyle($input, $output);
        $categories     = $this->categoryService->getAllCategory();
        $categoriesName = array_map(function ($category) {
            return [$category->getName()];
        }, $categories);

        $symfonyStyle->horizontalTable(
            ['Name'],
            $categoriesName
        );

        return Command::SUCCESS;
    }
}