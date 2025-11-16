<?php

namespace App\Service;

use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;

class TripService
{
    private EntityManagerInterface $em;
    private TripRepository $tripRepo;

    public function __construct(EntityManagerInterface $em, TripRepository $tripRepo)
    {
        $this->em = $em;
        $this->tripRepo = $tripRepo;
    }

    /**
     * Crée un nouveau trajet pour un chauffeur.
     */
    public function createTrip(User $driver, Vehicle $vehicle, array $data): Trip
    {
        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setVehicle($vehicle);
        $trip->setStartCity($data['startCity'] ?? '');
        $trip->setArrivalCity($data['arrivalCity'] ?? '');
        $trip->setStartAddress($data['startAddress'] ?? '');
        $trip->setArrivalAddress($data['arrivalAddress'] ?? '');
        $trip->setDepartureDate(new \DateTime($data['departureDate'] ?? 'now'));
        $trip->setDepartureTime(new \DateTime($data['departureTime'] ?? 'now'));
        $trip->setArrivalDate(new \DateTime($data['arrivalDate'] ?? 'now'));
        $trip->setArrivalTime(new \DateTime($data['arrivalTime'] ?? 'now'));
        $trip->setSeatsRemaining((int)($data['seatsRemaining'] ?? 1));
        $trip->setPrice((float)($data['price'] ?? 0));
        $trip->setIsEcological((bool)($data['isEcological'] ?? false));
        $trip->setDescription($data['description'] ?? null);
        $trip->setLuggage($data['luggage'] ?? null);
        $trip->setStatus('active');
        $trip->setCreatedAt(null);
        $trip->setUpdatedAt(null);

        $this->em->persist($trip);
        $this->em->flush();

        return $trip;
    }

    /**
     * Réserver un trajet pour un utilisateur.
     * Vérifie les places disponibles et les crédits.
     * Décrémente seatsRemaining et crédite l'utilisateur.
     */
    public function reserveTrip(User $user, Trip $trip): void
    {
        if ($trip->getSeatsRemaining() <= 0) {
            throw new \Exception("Aucune place disponible pour ce trajet.");
        }

        $totalPrice = $trip->getPrice() + 2; // frais supplémentaires
        if ($user->getCredits() < $totalPrice) {
            throw new \Exception("Crédits insuffisants pour réserver ce trajet.");
        }

        // Ajouter le passager
        $trip->addPassenger($user);

        // Mettre à jour les places et les crédits
        $trip->setSeatsRemaining($trip->getSeatsRemaining() - 1);
        $user->setCredits($user->getCredits() - $totalPrice);

        // Persister les changements
        $this->em->persist($trip);
        $this->em->persist($user);
        $this->em->flush();
    }
}
