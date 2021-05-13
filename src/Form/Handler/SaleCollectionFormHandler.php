<?php

namespace App\Form\Handler;

use App\Entity\SaleCollection;
use App\Form\DTO\SaleCollectionEditModel;
use App\Utils\Manager\SaleCollectionManager;

class SaleCollectionFormHandler
{
    /**
     * @var SaleCollectionManager
     */
    public $saleCollectionManager;

    public function __construct(SaleCollectionManager $saleCollectionManager)
    {
        $this->saleCollectionManager = $saleCollectionManager;
    }

    /**
     * @param SaleCollectionEditModel $saleCollectionEditModel
     *
     * @return SaleCollection
     */
    public function processSaleCollectionEditForm(SaleCollectionEditModel $saleCollectionEditModel): SaleCollection
    {
        $saleCollection = new SaleCollection();

        if ($saleCollectionEditModel->id) {
            $saleCollection = $this->saleCollectionManager->find($saleCollectionEditModel->id);
        }

        $saleCollection->setTitle($saleCollectionEditModel->title);
        $saleCollection->setDescription($saleCollectionEditModel->description);
        $saleCollection->setValidUntil($saleCollectionEditModel->validUntil);
        $saleCollection->setIsPublished($saleCollectionEditModel->isPublished);

        $this->saleCollectionManager->save($saleCollection);

        return $saleCollection;
    }
}
