<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/collection",
 *     attributes={
 *         "denormalization_context"={"groups"={"collection_put_mutable", "collection_post_mutable"}},
 *         "normalization_context"={"groups"={"collection_get_list"}}
 *     },
 *     collectionOperations={
 *         "get"= {
 *              "method"="GET",
 *              "normalization_context"={"groups"={"collection_get_list"}}
 *          },
 *         "post"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"collection_post_mutable"}}
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"collection_get_list"}}
 *          },
 *         "put"={
 *              "method"="PUT",
 *              "normalization_context"={"groups"={"collection_put_mutable"}}
 *          },
 *         "delete"={
 *              "method"="DELETE",
 *          }
 *     }
 *)
 * @ORM\Entity(repositoryClass="App\Repository\CollectionItemRepository")
 */
class CollectionItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"collection_put_mutable", "collection_post_mutable", "collection_get_list"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     * @Assert\NotNull(message="Entrez la position de la collection")
     */
    private $Position;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     * @Assert\NotNull(message="Nom de la collection svp")
     */
    private $collectionName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"collection_get_list"})
     */
    private $datePublished;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="https://schema.org/alternateName")
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     */
    private $alterntiveName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     */
    private $hasDiscount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     */
    private $discount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     * @Assert\NotNull(message="Renseignez l'affichage de la collection")
     */
    private $isCarousel;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     * @Assert\NotNull(message="Svp, dit-nous si la collection possède une bannier")
     */
    private $hasBanner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"collection_post_mutable", "collection_get_list", "collection_put_mutable"})
     */
    private $contentMediaBanner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CollectionBanner", mappedBy="collectionItem")
     * @Groups({"collection_get_list"})
     */
    private $collectionBanners;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="collectionItems")
     * @Groups({"collection_get_list"})
     */
    private $items;

    public function __construct()
    {
        $this->collectionBanners = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->Position;
    }

    public function setPosition(int $Position): self
    {
        $this->Position = $Position;

        return $this;
    }

    public function getCollectionName(): ?string
    {
        return $this->collectionName;
    }

    public function setCollectionName(?string $collectionName): self
    {
        $this->collectionName = $collectionName;

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

    public function getAlterntiveName(): ?string
    {
        return $this->alterntiveName;
    }

    public function setAlterntiveName(?string $alterntiveName): self
    {
        $this->alterntiveName = $alterntiveName;

        return $this;
    }

    public function getHasDiscount(): ?bool
    {
        return $this->hasDiscount;
    }

    public function setHasDiscount(?bool $hasDiscount): self
    {
        $this->hasDiscount = $hasDiscount;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getIsCarousel(): ?bool
    {
        return $this->isCarousel;
    }

    public function setIsCarousel(?bool $isCarousel): self
    {
        $this->isCarousel = $isCarousel;

        return $this;
    }

    public function getHasBanner(): ?bool
    {
        return $this->hasBanner;
    }

    public function setHasBanner(?bool $hasBanner): self
    {
        $this->hasBanner = $hasBanner;

        return $this;
    }

    public function getContentMediaBanner(): ?string
    {
        return $this->contentMediaBanner;
    }

    public function setContentMediaBanner(?string $contentMediaBanner): self
    {
        $this->contentMediaBanner = $contentMediaBanner;

        return $this;
    }

    /**
     * @return Collection|CollectionBanner[]
     */
    public function getCollectionBanners(): Collection
    {
        return $this->collectionBanners;
    }

    public function addCollectionBanner(CollectionBanner $collectionBanner): self
    {
        if (!$this->collectionBanners->contains($collectionBanner)) {
            $this->collectionBanners[] = $collectionBanner;
            $collectionBanner->setCollectionItem($this);
        }

        return $this;
    }

    public function removeCollectionBanner(CollectionBanner $collectionBanner): self
    {
        if ($this->collectionBanners->contains($collectionBanner)) {
            $this->collectionBanners->removeElement($collectionBanner);
            // set the owning side to null (unless already changed)
            if ($collectionBanner->getCollectionItem() === $this) {
                $collectionBanner->setCollectionItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Product $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addCollectionItem($this);
        }

        return $this;
    }

    public function removeItem(Product $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->removeCollectionItem($this);
        }

        return $this;
    }
}
