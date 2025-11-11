<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity]
class Preference
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['trip:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: "preference")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: "boolean")]
    #[Groups(['trip:read'])]
    private bool $animals = false;

    #[ORM\Column(type: "boolean")]
    #[Groups(['trip:read'])]
    private bool $smoke = false;

    #[ORM\Column(type: "boolean")]
    #[Groups(['trip:read'])]
    private bool $food = false;

    #[ORM\Column(type: "boolean")]
    #[Groups(['trip:read'])]
    private bool $is_custom = false;

    #[ORM\Column(type: "json", nullable: true)]
    #[Groups(['trip:read'])]
    private array $options = [];

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    // ------------------------
    // Getters et setters
    // ------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getAnimals(): bool
    {
        return $this->animals;
    }

    public function setAnimals(bool $animals): self
    {
        $this->animals = $animals;
        return $this;
    }

    public function getSmoke(): bool
    {
        return $this->smoke;
    }

    public function setSmoke(bool $smoke): self
    {
        $this->smoke = $smoke;
        return $this;
    }

    public function getFood(): bool
    {
        return $this->food;
    }

    public function setFood(bool $food): self
    {
        $this->food = $food;
        return $this;
    }

    public function getIsCustom(): bool
    {
        return $this->is_custom;
    }

    public function setIsCustom(bool $is_custom): self
    {
        $this->is_custom = $is_custom;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}
