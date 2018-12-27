<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ProductionDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SubCategory", inversedBy="items")
     */
    private $subCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand", inversedBy="items")
     */
    private $brand;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CollectionItem", inversedBy="items")
     */
    private $collectionItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="item")
     */
    private $orderProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="item")
     */
    private $media;

    public function __construct()
    {
        $this->collectionItems = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductionDate(): ?\DateTimeInterface
    {
        return $this->ProductionDate;
    }

    public function setProductionDate(?\DateTimeInterface $ProductionDate): self
    {
        $this->ProductionDate = $ProductionDate;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(?string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }

    public function setDatePublished(?\DateTimeInterface $datePublished): self
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    public function getSubCategory(): ?SubCategory
    {
        return $this->subCategory;
    }

    public function setSubCategory(?SubCategory $SubCategory): self
    {
        $this->subCategory = $SubCategory;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|CollectionItem[]
     */
    public function getCollectionItems(): Collection
    {
        return $this->collectionItems;
    }

    public function addCollectionItem(CollectionItem $collectionItem): self
    {
        if (!$this->collectionItems->contains($collectionItem)) {
            $this->collectionItems[] = $collectionItem;
        }

        return $this;
    }

    public function removeCollectionItem(CollectionItem $collectionItem): self
    {
        if ($this->collectionItems->contains($collectionItem)) {
            $this->collectionItems->removeElement($collectionItem);
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
            $orderProduct->setItem($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->removeElement($orderProduct);
            // set the owning side to null (unless already changed)
            if ($orderProduct->getItem() === $this) {
                $orderProduct->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setItem($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // set the owning side to null (unless already changed)
            if ($medium->getItem() === $this) {
                $medium->setItem(null);
            }
        }

        return $this;
    }
}
