<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"="product:list"}},
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"="product:list:write"}
 *          }
 *     },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"="product:item"}},
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"="product:item:write"}
 *          },
 *     },
 *     order={
 *         "id"="DESC",
 *         "price"="ASC"
 *     },
 *     attributes={
 *          "pagination_client_items_per_page"=true,
 *          "formats"={"jsonld", "json", "html", "jsonhal"}
 *     },
 *     paginationEnabled=true
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isHidden", "isDeleted"})
 * @ApiFilter(SearchFilter::class, properties={
 *     "category": "exact",
 *     "category.id": "partial"
 * })
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"product:list", "product:item", "order:item", "sale_collection:item", "sale_collection_product:list", "sale_collection_product:item", "cart:list", "cart:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"product:list", "product:list:write", "product:item", "order:item", "sale_collection:item", "sale_collection_product:list", "sale_collection_product:item", "cart:list", "cart:item"})
     */
    private $title;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     *
     * @Groups({"product:list", "product:list:write", "product:item", "order:item", "sale_collection:item", "sale_collection_product:list", "sale_collection_product:item", "cart:list", "cart:item"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"product:list", "product:list:write", "product:item", "order:item", "sale_collection:item"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"product:list", "product:item"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"product:list", "product:item"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     *
     * @Groups({"product:list", "product:item"})
     */
    private $size;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @Groups({"product:list", "product:list:write", "product:item", "order:item", "sale_collection:item"})
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity=ProductImage::class, mappedBy="product", orphanRemoval=true)
     *
     * @Groups({"product:list", "product:item", "cart:list", "cart:item", "sale_collection_product:list", "sale_collection_product:item"})
     */
    private $productImages;

    /**
     * @ORM\OneToMany(targetEntity=SaleCollectionProduct::class, mappedBy="product")
     */
    private $saleCollectionProducts;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="product")
     */
    private $orderProducts;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->isPublished = false;
        $this->isDeleted = false;
        $this->productImages = new ArrayCollection();
        $this->saleCollectionProducts = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

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

    /**
     * @return Collection|ProductImage[]
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): self
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages[] = $productImage;
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): self
    {
        if ($this->productImages->removeElement($productImage)) {
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SaleCollectionProduct[]
     */
    public function getSaleCollectionProducts(): Collection
    {
        return $this->saleCollectionProducts;
    }

    public function addSaleCollectionProduct(SaleCollectionProduct $saleCollectionProduct): self
    {
        if (!$this->saleCollectionProducts->contains($saleCollectionProduct)) {
            $this->saleCollectionProducts[] = $saleCollectionProduct;
            $saleCollectionProduct->setProduct($this);
        }

        return $this;
    }

    public function removeSaleCollectionProduct(SaleCollectionProduct $saleCollectionProduct): self
    {
        if ($this->saleCollectionProducts->removeElement($saleCollectionProduct)) {
            // set the owning side to null (unless already changed)
            if ($saleCollectionProduct->getProduct() === $this) {
                $saleCollectionProduct->setProduct(null);
            }
        }

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
            $orderProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getProduct() === $this) {
                $orderProduct->setProduct(null);
            }
        }

        return $this;
    }
}
