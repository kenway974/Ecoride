<?php

namespace App\Service;

use App\Repository\TripRepository;
use MongoDB\Client;

class SuggestionService
{
    private $collection;
    private $tripRepository;

    public function __construct(Client $client, TripRepository $tripRepository)
    {
        $this->collection = $client->selectCollection('covoiturage', 'suggestions');
        $this->tripRepository = $tripRepository;
    }

    /**
     * Ajouter un Trip aux suggestions
     */
    public function addTripToSuggestions($trip): void
    {
        $doc = [
            'tripId' => $trip->getId(),
            'from' => $trip->getStartCity(),
            'to' => $trip->getArrivalCity(),
            'date' => $trip->getDepartureDate()->format('Y-m-d'),
            'seatsRemaining' => $trip->getSeatsRemaining(),
            'price' => $trip->getPrice(),
            'isEcological' => $trip->isEcological(),
        ];

        $this->collection->insertOne($doc);
    }

    /**
     * Récupérer des suggestions
     */
    public function getSuggestions(string $from, string $to, string $date): array
    {
        // +/- 1 jour autour de la date demandée
        $docs = $this->collection->find([
            'from' => $from,
            'to' => $to,
            'date' => [
                '$gte' => $this->shiftDate($date, -1),
                '$lte' => $this->shiftDate($date, 1)
            ]
        ])->toArray();

        if (empty($docs)) {
            return [];
        }

        $tripIds = array_map(fn($doc) => $doc['tripId'], $docs);

        $queryBuilder = $this->tripRepository->createQueryBuilder('t')
            ->where('t.id IN (:ids)')
            ->setParameter('ids', $tripIds);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Décaler date
     */
    private function shiftDate(string $date, int $days): string
    {
        return (new \DateTime($date))->modify("$days days")->format('Y-m-d');
    }
}
