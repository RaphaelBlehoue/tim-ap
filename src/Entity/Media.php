<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
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
     * @ORM\Column(type="string", length=255)
     */
    private $url;

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
     */
    private $originalName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOnline;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasInOne;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="media")
     */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
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
}
