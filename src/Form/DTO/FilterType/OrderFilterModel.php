<?php

namespace App\Form\DTO\FilterType;

use App\Entity\Order;
use App\Entity\PromoCode;
use App\Entity\User;
use App\Form\DTO\OrderEditModel;
use Symfony\Component\Validator\Constraints as Assert;

class OrderFilterModel extends OrderEditModel
{
    /**
     * @var array
     */
    public $totalPrice;

    /**
     * @var array
     */
    public $createdAt;
}
