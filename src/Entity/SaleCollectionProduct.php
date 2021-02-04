<?php

namespace App\Entity;

use App\Repository\SaleCollectionProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SaleCollectionProductRepository::class)
 */
class SaleCollectionProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SaleCollection::class, inversedBy="saleCollectionProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $saleCollection;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="saleCollectionProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $discountAmount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaleCollection(): ?SaleCollection
    {
        return $this->saleCollection;
    }

    public function setSaleCollection(?SaleCollection $saleCollection): self
    {
        $this->saleCollection = $saleCollection;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDiscountAmount(): ?string
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(string $discountAmount): self
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }
}
