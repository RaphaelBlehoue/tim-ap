<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     iri="http://schema.org/Order",
 *     attributes={
 *         "denormalization_context"={"groups"={"order_put_mutable", "order_post_mutable"}},
 *         "normalization_context"={"groups"={"order_get_list"}}
 *     }
 *)
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/PostalAddress")
     */
    private $billingAddress;

    /**
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $orderStatus;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $orderDelivery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @ApiProperty(iri="http://schema.org/customer")
     */
    private $customer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isGift;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod", inversedBy="orders")
     */
    private $paymentMethod;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="orderedItem")
     */
    private $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(?\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getOrderStatus(): ?bool
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?bool $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getOrderDelivery(): ?string
    {
        return $this->orderDelivery;
    }

    public function setOrderDelivery(?string $orderDelivery): self
    {
        $this->orderDelivery = $orderDelivery;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getIsGift(): ?bool
    {
        return $this->isGift;
    }

    public function setIsGift(?bool $isGift): self
    {
        $this->isGift = $isGift;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

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
            $orderProduct->setOrderedItem($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->removeElement($orderProduct);
            // set the owning side to null (unless already changed)
            if ($orderProduct->getOrderedItem() === $this) {
                $orderProduct->setOrderedItem(null);
            }
        }

        return $this;
    }

}