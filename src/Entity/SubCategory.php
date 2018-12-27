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

/**
 * @ApiResource(
 *     attributes={
 *         "denormalization_context"={"groups"={"subcategory_put_mutable", "subcategory_post_mutable"}},
 *         "normalization_context"={"groups"={"subcategory_get_list"}}
 *     },
 *     collectionOperations={
 *         "get"= {
 *              "method"="GET",
 *              "normalization_context"={"groups"={"subcategory_get_list"}}
 *          },
 *         "post"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"subcategory_post_mutable"}}
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"subcategory_get_list"}}
 *          },
 *         "put"={
 *              "method"="PUT",
 *              "normalization_context"={"groups"={"subcategory_put_mutable"}}
 *          },
 *         "delete"={
 *              "method"="DELETE",
 *          }
 *     },
 *     subresourceOperations={
 *        ""={
 *              "method"="GET",
 *              "path"="/sub-category/{slug}/items"
 *          }
 *     }
 *)
 * @ORM\Entity(repositoryClass="App\Repository\SubCategoryRepository")
 */
class SubCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"subcategory_get_list", "subcategory_post_mutable", "subcategory_put_mutable"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Slug(fields={"name","id"}, updatable=true, separator=".")
     * @Groups({"subcategory_get_list"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"subcategory_get_list", "category_post_mutable", "category_put_mutable"})
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="subCategories")
     * @Groups({"subcategory_get_list"})
     */
    private $Category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="subCategory")
     * @ApiSubresource(maxDepth=1)
     * @Groups({"subcategory_get_list"})
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

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

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
            $item->setSubCategory($this);
        }

        return $this;
    }

    public function removeItem(Product $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getSubCategory() === $this) {
                $item->setSubCategory(null);
            }
        }

        return $this;
    }
}
