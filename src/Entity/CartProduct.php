<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CartProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CartProductRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "post"={"normalization_context"={"groups"="cart_product:write"}}
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"="cart_product:item"},
 *          },
 *          "patch"={
 *              "normalization_context"={"groups"="cart_product:write"},
 *          },
 *          "delete"={
 *
 *          }
 *     },
 *     attributes={
 *          "formats"={"jsonhal", "json", "jsonld"}
 *     },
 *     paginationEnabled=false
 * )
 */
class CartProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"cart:list", "cart:item", "cart_product:list", "cart_product:item"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartProducts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"cart_product:item", "cart_product:list", "cart_product:write"})
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"cart:list", "cart:item", "cart:write", "cart_product:item", "cart_product:list", "cart_product:write"})
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"cart:list", "cart:item", "cart:write", "cart_product:item", "cart_product:list", "cart_product:write"})
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

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
}
