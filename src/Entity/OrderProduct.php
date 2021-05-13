<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderProductRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "post"={"normalization_context"={"groups"="order_product:write"}}
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"="order_product:item"},
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *          "patch"={
 *              "normalization_context"={"groups"="order_product:write"},
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
class OrderProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"order:item", "order_product:list", "order_product:item"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderProducts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"order_product:list", "order_product:item", "order_product:write"})
     */
    private $appOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orderProducts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"order:item", "order:write", "order_product:list", "order_product:item", "order_product:write"})
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"order:item", "order:write", "order_product:list", "order_product:item", "order_product:write"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     *
     * @Groups({"order:item", "order:write", "order_product:list", "order_product:item", "order_product:write"})
     */
    private $pricePerOne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppOrder(): ?Order
    {
        return $this->appOrder;
    }

    public function setAppOrder(?Order $appOrder): self
    {
        $this->appOrder = $appOrder;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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
