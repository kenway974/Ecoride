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
            $trips = $tripRepository->findByCityAndDateSafe(
                $startCity,
                $arrivalCity,
                $date
            );

            // Serializer pour appliquer les groupes
            $data = $serializer->serialize($trips, 'json', ['groups' => ['trip:list']]);

            return JsonResponse::fromJsonString($data);
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

        // Serializer pour appliquer les groupes
        $data = $serializer->serialize($trip, 'json', ['groups' => ['trip:read']]);

        return JsonResponse::fromJsonString($data);
    }
}
