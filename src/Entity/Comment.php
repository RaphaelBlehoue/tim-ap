<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
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
     */
    private $commentText;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $commentTime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOnline;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAllUserViewOnline;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     */
    private $creator;

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
}
