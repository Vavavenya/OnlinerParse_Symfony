<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function getByOnlinerId(int $id): ?Product
    {
        return $this->findOneBy(['id' => $id]);
    }
}