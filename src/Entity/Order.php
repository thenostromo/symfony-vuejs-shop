<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     denormalizationContext={"groups"={"order:write"}},
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"="order:list"},
 *          },
 *          "post"={"normalization_context"={"groups"="order:write"}}
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"="order:item"},
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *     },
 *     attributes={
 *          "formats"={"jsonhal", "json", "jsonld"}
 *     },
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isHidden", "isDeleted"})
 * @ApiFilter(SearchFilter::class, properties={
 *     "owner": "exact",
 *     "owner.id": "partial"
 * })
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="app_order")
 */
class Order
{
    const STATUS_CREATED = 0;
    const STATUS_PROCESSED = 1;
    const STATUS_COMPLECTED = 2;
    const STATUS_DELIVERED = 3;
    const STATUS_DENIED = 4;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"order:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changedAt;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="appOrder", cascade={"persist"}, orphanRemoval=true)
     *
     * @Groups({"order:item", "order:write"})
     */
    private $orderProducts;

    /**
     * @ORM\ManyToOne(targetEntity=PromoCode::class, inversedBy="orders")
     *
     * @Groups({"order:item", "order:item:update"})
     */
    private $promoCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->status = self::STATUS_CREATED;
        $this->isDeleted = false;
        $this->orderProducts = new ArrayCollection();
        $this->changedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getChangedAt(): ?\DateTimeInterface
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeInterface $changedAt): self
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setAppOrder($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getAppOrder() === $this) {
                $orderProduct->setAppOrder(null);
            }
        }

        return $this;
    }

    public function getPromoCode(): ?PromoCode
    {
        return $this->promoCode;
    }

    public function setPromoCode(?PromoCode $promoCode): self
    {
        $this->promoCode = $promoCode;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
