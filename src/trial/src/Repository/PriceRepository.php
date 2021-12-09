<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PriceRepository extends EntityRepository
{
    // amount product in category
    // show products with prices

    public function getMaxPrice(): float
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT max(p.maxPrice)        
            FROM App\Entity\Price p');

        return array_shift($query->getResult()[0]); // getFirstResult
    }

    public function getMinPrice(): float
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('min(NULLIF(p.minPrice, 0))');

        return array_shift($qb->getQuery()->getResult()[0]);
    }
}