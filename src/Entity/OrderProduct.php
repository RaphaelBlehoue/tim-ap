<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\OrderProductRepository")
 */
class OrderProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $QuantitativeValue;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasDiscount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="orderProducts")
     */
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderProducts")
     */
    private $orderedItem;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantitativeValue(): ?int
    {
        return $this->QuantitativeValue;
    }

    public function setQuantitativeValue(int $QuantitativeValue): self
    {
        $this->QuantitativeValue = $QuantitativeValue;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

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

    public function getOrderedItem(): ?Order
    {
        return $this->orderedItem;
    }

    public function setOrderedItem(?Order $orderedItem): self
    {
        $this->orderedItem = $orderedItem;

        return $this;
    }
}
