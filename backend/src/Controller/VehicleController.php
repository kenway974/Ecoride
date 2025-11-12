<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Service\VehicleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) { $this->em = $em; }

    #[Route('/api/user/vehicles', name: 'api_user_vehicles', methods: ['POST'])]
    public function addVehicle(Request $request, VehicleService $vehicleService): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $vehicle = $vehicleService->addVehicle($user, $data);

        return new JsonResponse([
            'id' => $vehicle->getId(),
            'plate' => $vehicle->getPlate(),
            'brand' => $vehicle->getBrand(),
            'model' => $vehicle->getModel(),
            'release_year' => $vehicle->getReleaseYear(),
            'energy' => $vehicle->getEnergy(),
            'seats_total' => $vehicle->getSeatsTotal(),
            'seats_available' => $vehicle->getSeatsAvailable(),
            'created_at' => $vehicle->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $vehicle->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

}
