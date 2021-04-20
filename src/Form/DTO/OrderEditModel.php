<?php

namespace App\Form\DTO;

use App\Entity\Order;
use App\Entity\PromoCode;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class OrderEditModel
{
    /**
     * @var string
     */
    public $id;

    /**
     * @Assert\NotBlank(message="Please select a user")
     * @var User
     */
    public $owner;

    /**
     * @Assert\NotBlank(message="Please select a status")
     * @var int
     */
    public $status;

    /**
     * @var PromoCode|null
     */
    public $promoCode;

    public static function makeFromOrder(?Order $order): self
    {
        $model = new self();
        if (!$order) {
            return $model;
        }

        $model->id = $order->getId();
        $model->owner = $order->getOwner();
        $model->status = $order->getStatus();
        $model->promoCode = $order->getPromoCode();

        return $model;
    }
}
