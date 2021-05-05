<?php

namespace App\Utils\ApiPlatform\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\PromoCode;
use Doctrine\ORM\QueryBuilder;

class FilterPromoCodeQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->andWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->andWhere($queryBuilder, $resourceClass);
    }

    public function andWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (PromoCode::class !== $resourceClass) {
            return;
        }

        $dateTime = new \DateTimeImmutable();

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere(
            sprintf("%s.uses > '0'", $rootAlias)
        );
        $queryBuilder->andWhere(
            sprintf("%s.validUntil >= '%s'", $rootAlias, $dateTime->format('Y-m-d H:i:s'))
        );
        $queryBuilder->andWhere(
            sprintf("%s.isActive = '1'", $rootAlias)
        );
        $queryBuilder->andWhere(
            sprintf("%s.isDeleted = '0'", $rootAlias)
        );
    }
}
