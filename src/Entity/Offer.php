<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $availability;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAvailability;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $buyPrice;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActivePrice;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublished;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(?string $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getIsAvailability(): ?bool
    {
        return $this->isAvailability;
    }

    public function setIsAvailability(?bool $isAvailability): self
    {
        $this->isAvailability = $isAvailability;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBuyPrice(): ?int
    {
        return $this->buyPrice;
    }

    public function setBuyPrice(int $buyPrice): self
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    public function getIsActivePrice(): ?bool
    {
        return $this->isActivePrice;
    }

    public function setIsActivePrice(?bool $isActivePrice): self
    {
        $this->isActivePrice = $isActivePrice;

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
}
