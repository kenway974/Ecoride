<?php

namespace App\Controller;

use App\Repository\TripRepository;
use App\Repository\VehicleRepository;
use App\Service\JwtService;
use App\Service\SuggestionService;
use App\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class TripController extends AbstractController
{
    #[Route('/api/trips', name: 'api_trips', methods: ['GET'])]
    public function index(
        Request $request,
        TripRepository $tripRepository,
        SuggestionService $suggestionService,
        SerializerInterface $serializer,
        JwtService $jwtService
    ): JsonResponse
    {
        $startCity = $request->query->get('from');
        $arrivalCity = $request->query->get('to');
        $date = $request->query->get('date'); // YYYY-MM-DD ou null

        try {
            $user = $jwtService->validate($request);

            // 1️⃣ Recherche exacte dans MySQL
            $trips = $tripRepository->findByCityAndDateSafe($startCity, $arrivalCity, $date);

            // 2️⃣ Si aucun trip exact n’est trouvé, on propose des suggestions ±1 jour
            if (empty($trips) && $date) {
                $trips = $suggestionService->getSuggestions(
                    $startCity,
                    $arrivalCity,
                    $date,
                    $user ? $user->getId() : null
                );
            }

            $jsonTrips = $serializer->serialize($trips, 'json', [
                'groups' => ['trip:list'],
                'circular_reference_handler' => fn($object) => $object->getId(),
            ]);

            return new JsonResponse([
                'success' => true,
                'data' => json_decode($jsonTrips, true),
                'date' => $date
                
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des trips.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    #[Route('/api/trips/{id}', name: 'api_trip_show', methods: ['GET'])]
    public function show(int $id, TripRepository $tripRepository, SerializerInterface $serializer): JsonResponse
    {
        $trip = $tripRepository->findOneWithRelations($id);

        if (!$trip) {
            return $this->json([
                'success' => false,
                'message' => 'Trajet non trouvé.'
            ], 404);
        }

        // Sérialisation avec groupe spécifique pour le détail
        $jsonTrip = $serializer->serialize($trip, 'json', ['groups' => ['trip:read']]);

        return new JsonResponse([
            'success' => true,
            'data' => json_decode($jsonTrip, true)
        ]);
    }

    #[Route('/api/trips/{id}/reserve', name: 'trip_reserve', methods: ['POST'])]
    public function reserve(int $id, Request $request, JwtService $jwtService, TripService $tripService, TripRepository $tripRepository): JsonResponse
    {
        try {
            $user = $jwtService->validate($request);
            $trip = $tripRepository->findOneWithRelations($id);

            if (!$trip) {
                return $this->json(['success' => false, 'message' => 'Trajet introuvable'], 404);
            }

            $tripService->reserveTrip($user, $trip);

            return $this->json([
                'success' => true,
                'message' => 'Réservation effectuée avec succès',
                'remainingSeats' => $trip->getSeatsRemaining(),
                'userCredits' => $user->getCredits()
            ], 200);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    // -----------------------
    // POST /api/trips
    // -----------------------
    #[Route('/api/trip', name: 'create', methods: ['POST'])]
    public function create(Request $request, VehicleRepository $vehicleRepo, TripService $tripService, JwtService $jwtService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $driver = $jwtService->validate($request);

        if (!$driver) {
            return $this->json(['error' => 'Utilisateur non connecté'], 401);
        }

        $vehicle = $vehicleRepo->find($data['vehicleId'] ?? null);
        if (!$vehicle) {
            return $this->json(['error' => 'Véhicule introuvable'], 400);
        }

        try {
            $trip = $tripService->createTrip($driver, $vehicle, $data);

            return $this->json([
                'success' => true,
                'message' => 'Trajet créé avec succès',
                'tripId' => $trip->getId(),
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création du trajet',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
