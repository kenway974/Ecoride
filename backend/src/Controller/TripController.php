<?php

namespace App\Controller;

use App\Repository\TripRepository;
use App\Repository\VehicleRepository;
use App\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class TripController extends AbstractController
{
    #[Route('/api/trips', name: 'api_trips', methods: ['GET'])]
    public function index(Request $request, TripRepository $tripRepository): JsonResponse
    {
        $startCity = $request->query->get('from');
        $arrivalCity = $request->query->get('to');
        $date = $request->query->get('date'); // YYYY-MM-DD ou null

        try {
            $trips = $tripRepository->findByCityAndDateSafe(
                $startCity,
                $arrivalCity,
                $date
            );

            return $this->json([
                'success' => true,
                'data' => $trips,
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des trips.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // -----------------------
    // POST /api/trips
    // -----------------------
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, VehicleRepository $vehicleRepo, TripService $tripService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $driver = $this->getUser();

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
