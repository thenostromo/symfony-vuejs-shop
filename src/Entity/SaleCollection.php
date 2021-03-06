<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SaleCollectionRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     denormalizationContext={"groups"={"sale_collection:write"}},
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"="sale_collection:list"},
 *          },
 *          "post"={}
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"="sale_collection:item"},
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *     },
 *     attributes={
 *          "formats"={"jsonhal", "json", "jsonld"}
 *     },
 * )
 * @ORM\Entity(repositoryClass=SaleCollectionRepository::class)
 */
class SaleCollection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"sale_collection:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"sale_collection:list", "sale_collection:item"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"sale_collection:list", "sale_collection:item"})
     */
    private $validUntil;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\OneToMany(targetEntity=SaleCollectionProduct::class, mappedBy="saleCollection", cascade={"persist"}, orphanRemoval=true)
     *
     * @Groups({"sale_collection:item", "sale_collection:write"})
     */
    private $saleCollectionProducts;

    public function __construct()
    {
        $this->isPublished = false;
        $this->saleCollectionProducts = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(\DateTimeInterface $validUntil): self
    {
        $this->validUntil = $validUntil;

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
            $saleCollectionProduct->setSaleCollection($this);
        }

        return $this;
    }

    public function removeSaleCollectionProduct(SaleCollectionProduct $saleCollectionProduct): self
    {
        if ($this->saleCollectionProducts->removeElement($saleCollectionProduct)) {
            // set the owning side to null (unless already changed)
            if ($saleCollectionProduct->getSaleCollection() === $this) {
                $saleCollectionProduct->setSaleCollection(null);
            }
        }

        return $this;
    }
}
