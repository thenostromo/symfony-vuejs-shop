<?php

namespace App\Form\DTO;

use App\Entity\SaleCollection;
use Symfony\Component\Validator\Constraints as Assert;

class SaleCollectionEditModel
{
    /**
     * @var string
     */
    public $id;

    /**
     * @Assert\NotBlank(message="Please enter a title")
     *
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @Assert\NotBlank(message="Please choose a date")
     *
     * @var \DateTime
     */
    public $validUntil;

    /**
     * @var bool
     */
    public $isPublished;

    public static function makeFromSaleCollection(?SaleCollection $saleCollection): self
    {
        $model = new self();
        if (!$saleCollection) {
            return $model;
        }

        $model->id = $saleCollection->getId();
        $model->title = $saleCollection->getTitle();
        $model->description = $saleCollection->getDescription();
        $model->validUntil = $saleCollection->getValidUntil();
        $model->isPublished = $saleCollection->getIsPublished();

        return $model;
    }
}
