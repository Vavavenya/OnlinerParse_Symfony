<?php

namespace App\Services;

use App\Entity\Category;

use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCategory(string $categoryName): Category
    {
        $category = $this->entityManager
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        if (!$category){
            $category = new Category();
            $category->setName($categoryName);
        }

        return $category;
    }

    public function getAllCategory(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }
}
