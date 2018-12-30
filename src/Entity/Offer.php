<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/Offer",
 *     attributes={
 *         "denormalization_context"={"groups"={"offer.put", "offer.post"}},
 *         "normalization_context"={"groups"={"offer.read"}}
 *     }
 *)
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
     * @ApiProperty(iri="https://schema.org/availability")
     * @Groups({"product.read", "offer.read", "offer.put", "offer.post"})
     */
    private $availability;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"product.read", "offer.read", "offer.put", "offer.post"})
     */
    private $isAvailability;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Ajouter le prix")
     * @Groups({"product.read", "offer.read", "offer.put", "offer.post"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"offer.read", "offer.put", "offer.post"})
     */
    private $buyPrice;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"product.read", "offer.read", "offer.put", "offer.post"})
     */
    private $isActivePrice;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"offer.read"})
     */
    private $datePublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="offers")
     * @Groups({"offer.read"})
     */
    private $item;

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
