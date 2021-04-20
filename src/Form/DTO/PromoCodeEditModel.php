<?php

namespace App\Form\DTO;

use App\Entity\PromoCode;
use Symfony\Component\Validator\Constraints as Assert;

class PromoCodeEditModel
{
    /**
     * @var string
     */
    public $id;

    /**
     * @Assert\NotBlank(message="Please enter a title")
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @Assert\NotBlank(message="Please indicate the quantity")
     * @Assert\GreaterThanOrEqual(value="0")
     * @var int
     */
    public $uses;

    /**
     * @var string
     */
    public $value;

    /**
     * @Assert\NotBlank(message="Please enter a discount")
     * @var string
     */
    public $discount;

    /**
     * @Assert\NotBlank(message="Please select a date")
     * @var \DateTime
     */
    public $validUntil;

    /**
     * @var bool
     */
    public $isActive;

    public static function makeFromPromoCode(?PromoCode $promoCode): self
    {
        $promoCodeEditModel = new self();
        if (!$promoCode) {
            return $promoCodeEditModel;
        }

        $promoCodeEditModel->id = $promoCode->getId();
        $promoCodeEditModel->title = $promoCode->getTitle();
        $promoCodeEditModel->description = $promoCode->getDescription();
        $promoCodeEditModel->value = $promoCode->getValue();
        $promoCodeEditModel->discount = $promoCode->getDiscount();
        $promoCodeEditModel->uses = $promoCode->getUses();
        $promoCodeEditModel->validUntil = $promoCode->getValidUntil();
        $promoCodeEditModel->isActive = $promoCode->getIsActive();

        return $promoCodeEditModel;
    }
}
