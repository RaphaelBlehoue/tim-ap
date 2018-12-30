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
 *     iri="https://schema.org/Brand",
 *     attributes={
 *         "denormalization_context"={"groups"={"brand.put", "brand.post"}},
 *         "normalization_context"={"groups"={"brand.read"}}
 *     }
 *)
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 */
class Brand
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="nom du logo")
     * @Groups({"brand.read", "brand.put", "brand.post"})
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(message="Svp, veuillez entrer le nom de la marque")
     * @ApiProperty(iri="https://schema.org/name")
     * @Groups({"brand.read", "brand.put", "brand.post", "product.read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="brand")
     * @Groups({"brand.read"})
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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
            $item->setBrand($this);
        }

        return $this;
    }

    public function removeItem(Product $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getBrand() === $this) {
                $item->setBrand(null);
            }
        }

        return $this;
    }
}
