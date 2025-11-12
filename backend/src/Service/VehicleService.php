<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class VehicleService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addVehicle(User $user, array $data): Vehicle
    {
        $vehicle = new Vehicle();
        $vehicle->setOwner($user);
        $vehicle->setPlate($data['plate']);
        $vehicle->setBrand($data['brand']);
        $vehicle->setModel($data['model']);
        $vehicle->setReleaseYear((int)$data['release_year']);
        $vehicle->setEnergy($data['energy']);
        $vehicle->setSeatsTotal((int)$data['seats_total']);
        $vehicle->setSeatsAvailable((int)$data['seats_available']);
        $vehicle->setCreatedAt(new \DateTimeImmutable());
        $vehicle->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($vehicle);
        $this->em->flush();

        return $vehicle;
    }
}
