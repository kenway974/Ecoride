<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $start_adress = null;

    #[ORM\Column(length: 50)]
    private ?string $start_city = null;

    #[ORM\Column(length: 255)]
    private ?string $arrival_address = null;

    #[ORM\Column(length: 50)]
    private ?string $arrival_city = null;

    /**
     * @var Collection<int, Trip>
     */
    #[ORM\OneToMany(targetEntity: Trip::class, mappedBy: 'address')]
    private Collection $trips;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAdress(): ?string
    {
        return $this->start_adress;
    }

    public function setStartAdress(string $start_adress): static
    {
        $this->start_adress = $start_adress;

        return $this;
    }

    public function getStartCity(): ?string
    {
        return $this->start_city;
    }

    public function setStartCity(string $start_city): static
    {
        $this->start_city = $start_city;

        return $this;
    }

    public function getArrivalAddress(): ?string
    {
        return $this->arrival_address;
    }

    public function setArrivalAddress(string $arrival_address): static
    {
        $this->arrival_address = $arrival_address;

        return $this;
    }

    public function getArrivalCity(): ?string
    {
        return $this->arrival_city;
    }

    public function setArrivalCity(string $arrival_city): static
    {
        $this->arrival_city = $arrival_city;

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): static
    {
        if (!$this->trips->contains($trip)) {
            $this->trips->add($trip);
            $trip->setAddress($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getAddress() === $this) {
                $trip->setAddress(null);
            }
        }

        return $this;
    }
}
