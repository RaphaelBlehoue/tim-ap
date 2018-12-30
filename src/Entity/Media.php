<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/image",
 *     attributes={
 *         "denormalization_context"={"groups"={"media.put", "media.post"}},
 *         "normalization_context"={"groups"={"media.read"}}
 *     }
 *)
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaHeight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaWidth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="Nom original du fichier manquant")
     * @Groups({"media.read", "media.put", "media.post"})
     */
    private $originalName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"media.read", "media.put", "product.read"})
     */
    private $isOnline;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"media.read", "media.put", "product.read"})
     */
    private $hasInOne;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"media.read"})
     */
    private $datePublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="media")
     * @Groups({"media.read"})
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"media.read", "media.post", "product.read", "media.put"})
     * @Assert\NotNull(message="Url du l'image")
     */
    private $contentUrl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"media.read", "media.post", "product.read", "media.put"})
     * @Assert\NotNull(message="Url du l'image")
     */
    private $mimeType;

    public function __construct()
    {
        $this->hasInOne = false;
        $this->isOnline = true;
        $this->datePublished = new \DateTime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHasInOne(): ?bool
    {
        return $this->hasInOne;
    }

    public function setHasInOne(?bool $hasInOne): self
    {
        $this->hasInOne = $hasInOne;

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

    public function getItem(): ?Product
    {
        return $this->item;
    }

    public function setItem(?Product $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(?string $contentUrl): self
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}
