<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['trip:list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['trip:list'])]
    private ?User $driver = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['trip:list'])]
    private ?Vehicle $vehicle = null;

    #[ORM\Column(length: 255)]
    #[Groups(['trip:list'])]
    private ?string $startCity = null;

    #[ORM\Column(length: 255)]
    #[Groups(['trip:list'])]
    private ?string $arrivalCity = null;

    #[ORM\Column(length: 255)]
    #[Groups(['trip:list'])]
    private ?string $startAddress = null;

    #[ORM\Column(length: 255)]
    #[Groups(['trip:list'])]
    private ?string $arrivalAddress = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['trip:list'])]
    private ?\DateTime $departureTime = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['trip:list'])]
    private ?\DateTime $departureDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['trip:list'])]
    private ?\DateTime $arrivalDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['trip:list'])]
    private ?\DateTime $arrivalTime = null;

    #[ORM\Column]
    #[Groups(['trip:list'])]
    private ?int $seatsRemaining = null;

    #[ORM\Column]
    #[Groups(['trip:list'])]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['trip:list'])]
    private ?bool $isEcological = false;

    #[ORM\Column(length: 20)]
    #[Groups(['trip:list'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['trip:list'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['trip:list'])]
    private ?string $luggage = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'trip')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getDriver(): ?User { return $this->driver; }
    public function setDriver(?User $driver): static { $this->driver = $driver; return $this; }

    public function getVehicle(): ?Vehicle { return $this->vehicle; }
    public function setVehicle(?Vehicle $vehicle): static { $this->vehicle = $vehicle; return $this; }

    public function getStartCity(): ?string { return $this->startCity; }
    public function setStartCity(string $startCity): static { $this->startCity = $startCity; return $this; }

    public function getArrivalCity(): ?string { return $this->arrivalCity; }
    public function setArrivalCity(string $arrivalCity): static { $this->arrivalCity = $arrivalCity; return $this; }

    public function getStartAddress(): ?string { return $this->startAddress; }
    public function setStartAddress(string $startAddress): static { $this->startAddress = $startAddress; return $this; }

    public function getArrivalAddress(): ?string { return $this->arrivalAddress; }
    public function setArrivalAddress(string $arrivalAddress): static { $this->arrivalAddress = $arrivalAddress; return $this; }

    public function getDepartureTime(): ?\DateTime { return $this->departureTime; }
    public function setDepartureTime(\DateTime $departureTime): static { $this->departureTime = $departureTime; return $this; }

    public function getDepartureDate(): ?\DateTime { return $this->departureDate; }
    public function setDepartureDate(\DateTime $departureDate): static { $this->departureDate = $departureDate; return $this; }

    public function getArrivalDate(): ?\DateTime { return $this->arrivalDate; }
    public function setArrivalDate(\DateTime $arrivalDate): static { $this->arrivalDate = $arrivalDate; return $this; }

    public function getArrivalTime(): ?\DateTime { return $this->arrivalTime; }
    public function setArrivalTime(\DateTime $arrivalTime): static { $this->arrivalTime = $arrivalTime; return $this; }

    public function getSeatsRemaining(): ?int { return $this->seatsRemaining; }
    public function setSeatsRemaining(int $seatsRemaining): static { $this->seatsRemaining = $seatsRemaining; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): static { $this->price = $price; return $this; }

    public function isEcological(): ?bool { return $this->isEcological; }
    public function setIsEcological(bool $isEcological): static { $this->isEcological = $isEcological; return $this; }

    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getLuggage(): ?string { return $this->luggage; }
    public function setLuggage(?string $luggage): static { $this->luggage = $luggage; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(?\DateTimeImmutable $createdAt): static {
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static {
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
        return $this;
    }
}
