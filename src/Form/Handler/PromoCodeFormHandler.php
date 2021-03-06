<?php

namespace App\Form\Handler;

use App\Entity\PromoCode;
use App\Form\DTO\PromoCodeEditModel;
use App\Utils\Manager\PromoCodeManager;

class PromoCodeFormHandler
{
    /**
     * @var PromoCodeManager
     */
    public $promoCodeManager;

    public function __construct(PromoCodeManager $promoCodeManager)
    {
        $this->promoCodeManager = $promoCodeManager;
    }

    /**
     * @param PromoCodeEditModel $promoCodeEditModel
     * @return PromoCode
     */
    public function processEditForm(PromoCodeEditModel $promoCodeEditModel): PromoCode
    {
        $promoCode = new PromoCode();

        if ($promoCodeEditModel->id) {
            $promoCode = $this->promoCodeManager->find($promoCodeEditModel->id);
        }

        $value = $this->promoCodeManager->getFormattedValue($promoCodeEditModel->value);

        $promoCode->setValue($value);
        $promoCode->setTitle($promoCodeEditModel->title);
        $promoCode->setDescription($promoCodeEditModel->description);
        $promoCode->setDiscount($promoCodeEditModel->discount);
        $promoCode->setUses($promoCodeEditModel->uses);
        $promoCode->setValidUntil($promoCodeEditModel->validUntil);
        $promoCode->setIsActive($promoCodeEditModel->isActive);

        $this->promoCodeManager->save($promoCode);

        return $promoCode;
    }
}
