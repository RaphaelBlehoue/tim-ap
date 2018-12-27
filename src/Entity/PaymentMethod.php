<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/PaymentMethod",
 *     attributes={
 *         "denormalization_context"={"groups"={"payment_put_mutable", "payment_post_mutable"}},
 *         "normalization_context"={"groups"={"payment_get_list"}}
 *     },
 *     collectionOperations={
 *         "get"= {
 *              "method"="GET",
 *              "normalization_context"={"groups"={"payment_get_list"}}
 *          },
 *         "post"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"payment_post_mutable"}}
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"payment_get_list"}}
 *          },
 *         "put"={
 *              "method"="PUT",
 *              "normalization_context"={"groups"={"payment_put_mutable"}}
 *          },
 *         "delete"={
 *              "method"="DELETE",
 *          }
 *     }
 *)
 * @ORM\Entity(repositoryClass="App\Repository\PaymentMethodRepository")
 */
class PaymentMethod
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"payment_get_list"})
     */
    private $paymentUrl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(message="Entrez le nom du type de paiement")
     * @Groups({"payment_get_list", "payment_put_mutable", "payment_post_mutable"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"payment_get_list", "payment_put_mutable", "payment_post_mutable"})
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="paymentMethod")
     * @Groups({"payment_get_list"})
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): self
    {
        $this->paymentUrl = $paymentUrl;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setPaymentMethod($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getPaymentMethod() === $this) {
                $order->setPaymentMethod(null);
            }
        }

        return $this;
    }
}
