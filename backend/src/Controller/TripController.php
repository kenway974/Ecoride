<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class TripController extends AbstractController
{
    #[Route('/api/trips', name: 'api_trips', methods: ['GET'])]
    public function index(Request $request, TripRepository $tripRepository, SerializerInterface $serializer): JsonResponse
    {
        $startCity = $request->query->get('from');
        $arrivalCity = $request->query->get('to');
        $date = $request->query->get('date'); // YYYY-MM-DD ou null

        try {
            $trips = $tripRepository->findByCityAndDateSafe($startCity, $arrivalCity, $date);

            // Sérialisation avec groupe
            $jsonTrips = $serializer->serialize($trips, 'json', ['groups' => ['trip:list']]);

            // Retourner un JSON complet avec "success" et "data"
            return new JsonResponse([
                'success' => true,
                'data' => json_decode($jsonTrips, true)
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
}
