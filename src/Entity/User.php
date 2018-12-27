<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\UserAuth;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints AS Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * @ApiResource(
 *     iri="http://schema.org/Person",
 *     description="User information to register in Database",
 *     paginationItemsPerPage=25,
 *     attributes={
 *         "denormalization_context"={"groups"={"user_put_mutable", "user_post_mutable"}},
 *         "normalization_context"={"groups"={"user_get_list"}},
 *         "validation_groups"={"user_register_valid"}
 *     },
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"user_get_list"}}
 *          },
 *          "register"={
 *              "route_name"="api_register",
 *              "path"="api/register",
 *              "controller"=UserAuth::class,
 *              "method"="POST",
 *              "denormalization_context"={"groups"={"user_post_mutable"}},
 *              "validation_groups"={"user_register_valid"}
 *          }
 *     }
 * )
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"},message="Cette adresse email existe déjà", groups={"user_register_valid"})
 * @UniqueEntity(fields={"telephone"},message="Ce numero de téléphone est déjà utilisé", groups={"user_register_valid"})
 */
class User implements UserInterface
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Groups({"user_get_list", "user_put_mutable", "user_post_mutable"})
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotNull(message="Entrez une adresse email", groups={"user_register_valid"})
     * @Assert\Email(message="le format de l'adresse email est invalide")
     */
    protected $email;

    /**
     * @Groups({"user_get_list"})
     * @Gedmo\Slug(fields={"familyName","id", "additionalName"}, updatable=true, separator=".")
     * @ORM\Column(length=128, unique=true)
     */
    protected  $username;

    /**
     * @Groups({"user_get_list", "user_put_mutable", "user_post_mutable"})
     * @ORM\Column(type="phone_number", unique=true, nullable=true)
     * @Assert\NotNull(message="Entrez un numero de téléphone valide", groups={"user_register_valid"})
     * @AssertPhoneNumber(defaultRegion="ANY", groups={"user_register_valid"})
     */
    protected $telephone;

    /**
     * @var
     * @Groups({"user_get_list", "user_put_mutable", "user_post_mutable"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="Entrez vos prénoms", groups={"user_register_valid"})
     */
    protected $additionalName;

    /**
     * @var
     * @Groups({"user_get_list", "user_put_mutable", "user_post_mutable"})
     * @Assert\NotNull(message="Entrez votre nom de famille", groups={"user_register_valid"})
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $familyName;

    /**
     * @var
     * @Groups({"user_get_list", "user_post_mutable"})
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotNull(message="Date de naissance incorrect")
     */
    protected $birthDate;

    /**
     * @Groups({"user_get_list", "user_post_mutable"})
     * @Assert\NotNull(message="Genre incorrect")
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    protected $gender;

    /**
     * @Groups({"user_get_list", "user_post_mutable", "user_put_mutable"})
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotNull(message="Entrez votre Adresse")
     */
    protected $address;

    /**
     * @Groups({"user_get_list"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isActive;


    /**
     * @Groups({"user_get_list"})
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $created;

    /**
     * @Groups({"user_get_list", "user_post_mutable", "user_put_mutable"})
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @Groups({"user_post_mutable", "user_put_mutable"})
     * @var string The hashed password
     * @Assert\NotNull(message="Entrez vote mot de passe", groups={"user_register_valid"})
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="customer", orphanRemoval=true)
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="creator")
     */
    private $comments;


    public function __construct()
    {
        $this->isActive = true;
        $this->created = new \DateTime('now');
        $this->orders = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRoles($roles): self
    {
        return in_array(strtoupper($roles), $this->getRoles(), true);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdditionalName(): ?string
    {
        return $this->additionalName;
    }

    public function setAdditionalName(?string $additionalName): self
    {
        $this->additionalName = $additionalName;

        return $this;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setFamilyName(?string $familyName): self
    {
        $this->familyName = $familyName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

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
            $order->setSeller($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getSeller() === $this) {
                $order->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCreator($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCreator() === $this) {
                $comment->setCreator(null);
            }
        }

        return $this;
    }
}
