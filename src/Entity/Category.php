<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/category",
 *     attributes={
 *         "denormalization_context"={"groups"={"category.read", "category.read"}},
 *         "normalization_context"={"groups"={"category.read"}}
 *     },
 *     collectionOperations={
 *         "get"= {
 *              "method"="GET",
 *              "normalization_context"={"groups"={"category.read"}}
 *          },
 *         "post"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"category.read"}}
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"category.read"}}
 *          },
 *         "put"={
 *              "method"="PUT",
 *              "normalization_context"={"groups"={"category.read"}}
 *          },
 *         "delete"={
 *              "method"="DELETE",
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotNull(message="Veuillez entrez le nom de la category")
     * @Groups({"category.read", "category.read", "category.read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Slug(fields={"name","id"}, updatable=true, separator=".")
     * @Groups({"category.read"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"category.read", "category.read", "category.read"})
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="Entrez la nom de la class de l'icon")
     * @Groups({"category.read", "category.read", "category.read"})
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SubCategory", mappedBy="Category")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * @ApiSubresource(maxDepth=1)
     * @Groups({"category.read"})
     */
    private $subCategories;


    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection|SubCategory[]
     */
    public function getSubCategories(): Collection
    {
        return $this->subCategories;
    }

    public function addSubCategory(SubCategory $subCategory): self
    {
        if (!$this->subCategories->contains($subCategory)) {
            $this->subCategories[] = $subCategory;
            $subCategory->setCategory($this);
        }

        return $this;
    }

    public function removeSubCategory(SubCategory $subCategory): self
    {
        if ($this->subCategories->contains($subCategory)) {
            $this->subCategories->removeElement($subCategory);
            // set the owning side to null (unless already changed)
            if ($subCategory->getCategory() === $this) {
                $subCategory->setCategory(null);
            }
        }

        return $this;
    }
}
