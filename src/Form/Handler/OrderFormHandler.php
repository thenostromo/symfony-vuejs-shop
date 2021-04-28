<?php

namespace App\Form\Handler;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\DTO\OrderEditModel;
use App\Utils\Manager\OrderManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderFormHandler
{
    /**
     * @var OrderManager
     */
    public $orderManager;

    /**
     * @var FilterBuilderUpdater
     */
    private $filterBuilderUpdater;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function __construct(OrderManager $orderManager, PaginatorInterface $paginator, FilterBuilderUpdater $filterBuilderUpdater)
    {
        $this->orderManager = $orderManager;
        $this->paginator = $paginator;
        $this->filterBuilderUpdater = $filterBuilderUpdater;
    }

    /**
     * @param Request       $request
     * @param FormInterface $filterForm
     *
     * @return PaginationInterface
     */
    public function processOrderFiltersForm(Request $request, FormInterface $filterForm): PaginationInterface
    {
        $queryBuilder = $this->orderManager->getRepository()
            ->createQueryBuilder('o')
            ->leftJoin('o.owner', 'u')
            ->where('o.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false);

        if ($filterForm->isSubmitted()) {
            $this->filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }

        return $this->paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1)
        );
    }

    /**
     * @param OrderEditModel $orderEditModel
     *
     * @return Order
     */
    public function processOrderEditForm(OrderEditModel $orderEditModel): Order
    {
        $order = new Order();

        if ($orderEditModel->id) {
            $order = $this->orderManager->find($orderEditModel->id);
        }

        $totalPrice = 0;
        $totalPriceWithDiscount = 0;

        $order->setOwner($orderEditModel->owner);
        $order->setStatus($orderEditModel->status);
        $order->setPromoCode($orderEditModel->promoCode);
        $order->setTotalPrice($totalPrice);

        $orderProducts = $order->getOrderProducts()->getValues();

        /** @var OrderProduct $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            $totalPrice += $orderProduct->getQuantity() * $orderProduct->getPricePerOne();
        }

        $promoCode = $order->getPromoCode();
        if ($promoCode) {
            $promoCodeDiscount = $promoCode->getDiscount();
            $totalPriceWithDiscount = $totalPrice - (($totalPrice / 100) * $promoCodeDiscount);
        }

        $order->setTotalPrice($totalPriceWithDiscount);

        $this->orderManager->save($order);

        return $order;
    }
}
