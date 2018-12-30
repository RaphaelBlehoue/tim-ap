<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *         "denormalization_context"={"groups"={"collection.banner.put", "collection.banner.post"}},
 *         "normalization_context"={"groups"={"collection.banner.read"}}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CollectionBannerRepository")
 */
class CollectionBanner
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="Chemin du fichier inexistant")
     * @Groups({"collection.banner.read", "collection.banner.put", "collection.banner.post", "collection.read"})
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"collection.banner.read", "collection.read"})
     */
    private $mediaHeight;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"collection.banner.read", "collection.read"})
     */
    private $mediaWidth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"collection.banner.read"})
     */
    private $originalName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"collection.banner.read", "collection.read"})
     */
    private $isOnline;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotNull(message="Date de debut de publication")
     * @Groups({"collection.banner.read", "collection.banner.put", "collection.banner.post"})
     */
    private $datePublished;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotNull(message="Date de fin de publication")
     * @Groups({"collection.banner.read", "collection.banner.put", "collection.banner.post", "collection.read"})
     */
    private $dateEnd;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CollectionItem", inversedBy="collectionBanners")
     * @Groups({"collection.banner.read"})
     */
    private $collectionItem;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMediaHeight(): ?string
    {
        return $this->mediaHeight;
    }

    public function setMediaHeight(?string $mediaHeight): self
    {
        $this->mediaHeight = $mediaHeight;

        return $this;
    }

    public function getMediaWidth(): ?string
    {
        return $this->mediaWidth;
    }

    public function setMediaWidth(?string $mediaWidth): self
    {
        $this->mediaWidth = $mediaWidth;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(?bool $isOnline): self
    {
        $this->isOnline = $isOnline;

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

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getCollectionItem(): ?CollectionItem
    {
        return $this->collectionItem;
    }

    public function setCollectionItem(?CollectionItem $collectionItem): self
    {
        $this->collectionItem = $collectionItem;

        return $this;
    }
}
