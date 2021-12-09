<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function getCategoryWithAmountProducts(): array
    {
        $query = $this->createQueryBuilder('c')
            ->select('c.name, COUNT(prod.id)')
            ->innerJoin('c.products', 'p')
//            ->innerJoin(Product::class,'prod', 'p.product_id = prod.id')
            ->groupBy('c.name')
            ->getQuery();



//        $query = $this->createQueryBuilder('c')
//            ->select('c.name, prod.id')
//            ->innerJoin('c.products', 'p', 'c.id = p.category_id')
//            ->innerJoin(Product::class,'prod', 'p.product_id = prod.id')
//            ->groupBy('c.name')
//            ->getQuery();

        return $query->getResult();
    }

    //$query = $this->createQueryBuilder('s');
//$query->select('s, MAX(s.score) AS max_score');
//$query->where('s.challenge = :challenge')->setParameter('challenge', $challenge);
//$query->groupBy('s.user');
//$query->setMaxResults($limit);
//$query->orderBy('max_score', 'DESC');
//
//return $query->getQuery()->getResult();
}