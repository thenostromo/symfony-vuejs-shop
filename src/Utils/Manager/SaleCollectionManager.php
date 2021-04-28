<?php

namespace App\Utils\Manager;

use App\Entity\Order;
use App\Entity\SaleCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class SaleCollectionManager extends AbstractBaseManager
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(SaleCollection::class);
    }
}
