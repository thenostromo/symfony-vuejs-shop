<?php

namespace App\Entity;

use App\Repository\SaleCollectionProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=SaleCollectionProductRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "post"={"normalization_context"={"groups"="sale_collection_product:write"}}
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"="sale_collection_product:item"},
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *          "patch"={
 *              "normalization_context"={"groups"="sale_collection_product:write"},
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     attributes={
 *          "formats"={"jsonhal", "json", "jsonld"}
 *     },
 *     paginationEnabled=false
 * )
 */
class SaleCollectionProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"sale_collection:item", "sale_collection_product:list", "sale_collection_product:item"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SaleCollection::class, inversedBy="saleCollectionProducts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"sale_collection_product:list", "sale_collection_product:item", "sale_collection_product:write"})
     */
    private $saleCollection;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="saleCollectionProducts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"sale_collection:item", "sale_collection:write", "sale_collection_product:list", "sale_collection_product:item", "sale_collection_product:write"})
     */
    private $product;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     *
     * @Groups({"sale_collection:item", "sale_collection:write", "sale_collection_product:list", "sale_collection_product:item", "sale_collection_product:write"})
     */
    private $pricePerOne;

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

    public function getPricePerOne(): ?string
    {
        return $this->pricePerOne;
    }

    public function setPricePerOne(string $pricePerOne): self
    {
        $this->pricePerOne = $pricePerOne;

        return $this;
    }
}
