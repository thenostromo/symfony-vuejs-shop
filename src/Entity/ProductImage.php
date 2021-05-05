<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="product_image:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="product:item"}}},
 * )
 * @ORM\Entity(repositoryClass=ProductImageRepository::class)
 */
class ProductImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:list", "product:item", "cart:list", "cart:item", "sale_collection_product:list", "sale_collection_product:item"})
     */
    private $filenameBig;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:list", "product:item", "cart:list", "cart:item", "sale_collection_product:list", "sale_collection_product:item"})
     */
    private $filenameMiddle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:list", "product:item", "cart:list", "cart:item", "sale_collection_product:list", "sale_collection_product:item"})
     */
    private $filenameSmall;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFilenameBig(): ?string
    {
        return $this->filenameBig;
    }

    public function setFilenameBig(string $filenameBig): self
    {
        $this->filenameBig = $filenameBig;

        return $this;
    }

    public function getFilenameMiddle(): ?string
    {
        return $this->filenameMiddle;
    }

    public function setFilenameMiddle(string $filenameMiddle): self
    {
        $this->filenameMiddle = $filenameMiddle;

        return $this;
    }

    public function getFilenameSmall(): ?string
    {
        return $this->filenameSmall;
    }

    public function setFilenameSmall(string $filenameSmall): self
    {
        $this->filenameSmall = $filenameSmall;

        return $this;
    }
}
