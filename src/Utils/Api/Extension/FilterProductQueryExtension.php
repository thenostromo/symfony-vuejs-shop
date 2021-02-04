<?php

namespace App\Utils\Api\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;

class FilterProductQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (Product::class === $resourceClass) {
            $qb->andWhere(sprintf("%s.isHidden = '0'", $qb->getRootAliases()[0]));
            $qb->andWhere(sprintf("%s.isDeleted = '0'", $qb->getRootAliases()[0]));
        }
    }

    public function applyToItem(QueryBuilder $qb, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        if (Product::class === $resourceClass) {
            $qb->andWhere(sprintf("%s.isHidden = '0'", $qb->getRootAliases()[0]));
            $qb->andWhere(sprintf("%s.isDeleted = '0'", $qb->getRootAliases()[0]));
        }
    }
}