<?php

namespace App\Repository;

use App\Entity\PromoCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromoCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromoCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromoCode[]    findAll()
 * @method PromoCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromoCode::class);
    }

    /**
     * @return PromoCode[]
     */
    public function getActiveList()
    {
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.isDeleted = :isDeleted')
            ->orderBy('pc.id', 'ASC')
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param string $promoCodeValue
     *
     * @return PromoCode
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getValidPromoCodeByValue(string $promoCodeValue)
    {
        $currentDateTime = new \DateTime();
        $qb = $this->createQueryBuilder('pc');

        return $qb
            ->andWhere(
                $qb->expr()->gte('pc.validUntil', ':currentDateTime')
            )
            ->andWhere(
                $qb->expr()->gte('pc.uses', ':usesMin')
            )
            ->andWhere('pc.isHidden = :isHidden')
            ->andWhere('pc.isDeleted = :isDeleted')
            ->orderBy('pc.id', 'ASC')
            ->setParameters([
                'currentDateTime' => $currentDateTime,
                'usesMin' => 0,
                'isHidden' => false,
                'isDeleted' => false,
            ])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
