<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/Product",
 *     attributes={
 *         "denormalization_context"={"groups"={"product.put", "product.post"}},
 *         "normalization_context"={"groups"={"product.read"}}
 *     }
 *)
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
     * @Assert\NotNull(message="Entrez la date d'expiration du produit")
     * @Groups({"product.read", "product.post", "product.put"})
     */
    private $ProductionDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     * @Assert\NotNull(message="Entrez le SKU du product")
     * @Groups({"product.read", "product.post", "product.put", "brand.read", "collection.read", "order_product.read", "subcategory.read", "offer.read", "media.read"})
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"product.read", "product.post", "product.put", "brand.read", "collection.read", "subcategory.read"})
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"product.read", "product.post", "product.put", "brand.read", "collection.read", "subcategory.read"})
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="Le nom du produit svp")
     * @Groups({"product.read", "product.post", "product.put", "brand.read", "collection.read", "order_product.read", "subcategory.read", "offer.read", "media.read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotNull(message="Description du produit svp")
     * @Groups({"product.read", "product.post", "product.put", "brand.read", "collection.read", "subcategory.read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"product.read", "subcategory.read"})
     */
    private $datePublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SubCategory", inversedBy="items")
     * @Groups({"product.read"})
     */
    private $subCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand", inversedBy="items")
     * @Groups({"product.read", "collection.read"})
     */
    private $brand;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CollectionItem", inversedBy="items")
     * @Groups({"product.read"})
     */
    private $collectionItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="item")
     * @Groups({"product.read"})
     * @ApiSubresource(maxDepth=1)
     */
    private $orderProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="item", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ApiSubresource(maxDepth=1)
     * @Groups({"product.read"})
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="item")
     * @Groups({"product.read"})
     * @ApiSubresource(maxDepth=1)
     */
    private $offers;

    public function __construct()
    {
        $this->collectionItems = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->datePublished = new \DateTime('now');
        $this->offers = new ArrayCollection();
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

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setItem($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getItem() === $this) {
                $offer->setItem(null);
            }
        }

        return $this;
    }
}
