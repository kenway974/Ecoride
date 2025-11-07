<?php

namespace App\Controller;

use App\Repository\TripRepository;
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
}
