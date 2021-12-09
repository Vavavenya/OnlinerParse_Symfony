<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Price;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ProductService
{
    public EntityManagerInterface $entityManager;
    public DenormalizerInterface $denormalizer;

    public function __construct(
        EntityManagerInterface $entityManager,
        DenormalizerInterface $denormalizer
    )
    {
        $this->entityManager = $entityManager;
        $this->denormalizer  = $denormalizer;
    }

    public function insertRecords($records, Category $category): void
    {
        $allProducts = new ArrayCollection($this->entityManager->getRepository(Product::class)->findAll());

        foreach ($records as $record) {
            $product = $record['product'];
            $productObject = $this->getProductByOnlinerId($product, $allProducts);
            if (!isset($productObject)) {
                $productObject = $this->denormalizer->denormalize($product, Product::class, 'json');
            } elseif ($productObject->isNotUpdatedToday()) {
                $productObject->setDateLastUpdateValue();
            } else {
                continue;
            }

            $priceObject = $this->denormalizer->denormalize($record['price'], Price::class, 'json');

            $productObject->addPrice($priceObject);
            $productObject->addCategory($category);
            $this->entityManager->persist($productObject);
        }

        ChangeSetService::appendByUnitOfWork($this->entityManager);

        $this->entityManager->flush();
    }

    public function getAllProducts(): array
    {
        return $this->entityManager->getRepository(Product::class)->findAll();
    }

    private function getProductByOnlinerId(array $product, ArrayCollection $allProducts): ?Product
    {
        $product = $allProducts->filter(function (Product $existingProduct) use ($product) {
            return $product['onlinerId'] === $existingProduct->getOnlinerId();
        })->first();

        return $product ?: null;
    }
}