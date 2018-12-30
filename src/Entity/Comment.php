<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
/**
 * @ApiResource(
 *     iri="https://schema.org/Comment",
 *     attributes={
 *         "denormalization_context"={"groups"={"comment.put", "comment.post", "comment.put.field"}},
 *         "normalization_context"={"groups"={"comment.read"}}
 *     }
 *)
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotNull(message="le commentaire est vide")
     * @Groups({"comment.read", "comment.post", "comment.put"})
     */
    private $commentText;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"comment.read"})
     */
    private $commentTime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"comment.read", "comment.post", "comment.put.field"})
     */
    private $isOnline;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"comment.read", "comment.put"})
     */
    private $isAllUserViewOnline;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @Groups({"comment.read"})
     */
    private $creator;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"comment.read"})
     */
    private $datePublished;

    public function __construct()
    {
        $this->datePublished = new \DateTime("now");
        $this->isAllUserViewOnline = false;
        $this->isOnline = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentText(): ?string
    {
        return $this->commentText;
    }

    public function setCommentText(?string $commentText): self
    {
        $this->commentText = $commentText;

        return $this;
    }

    public function getCommentTime(): ?\DateTimeInterface
    {
        return $this->commentTime;
    }

    public function setCommentTime(?\DateTimeInterface $commentTime): self
    {
        $this->commentTime = $commentTime;

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

    public function getIsAllUserViewOnline(): ?bool
    {
        return $this->isAllUserViewOnline;
    }

    public function setIsAllUserViewOnline(?bool $isAllUserViewOnline): self
    {
        $this->isAllUserViewOnline = $isAllUserViewOnline;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

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
