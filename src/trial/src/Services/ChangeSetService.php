<?php

namespace App\Services;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;

class ChangeSetService
{
    const INSERTED = 'inserted';
    const UPDATED = 'updated';

    private static array $changeList = [];

    public static function appendByUnitOfWork(EntityManagerInterface $em): void
    {
        $unitOfWork = $em->getUnitOfWork();
        $unitOfWork->computeChangeSets();

        self::countChanges($unitOfWork->getScheduledEntityInsertions(), self::INSERTED);
        self::countChanges($unitOfWork->getScheduledEntityUpdates(), self::UPDATED);
    }

    public static function getChangeList(): array
    {
        return self::$changeList;
    }

    private static function countChanges(array $listOfEntity, string $nameOfActions): void
    {
        foreach ($listOfEntity as $entity) {
            $className = ClassUtils::getClass($entity);
            if (isset(self::$changeList[$nameOfActions][$className])) {
                self::$changeList[$nameOfActions][$className]++;
            } else {
                self::$changeList[$nameOfActions][$className] = 1;
            }
        }
    }
}