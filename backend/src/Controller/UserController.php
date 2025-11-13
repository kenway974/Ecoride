<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * ✅ Route pour mettre à jour le rôle d’un utilisateur connecté
     */
    #[Route('/api/user/role', name: 'api_user_role', methods: ['PUT'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updateRole(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $role = $data['role'] ?? 'ROLE_PASSAGER';

        $user->setRoles([$role]);
        $this->em->flush();

        return new JsonResponse(['message' => 'Rôle mis à jour', 'role' => $role]);
    }

    /**
     * ✅ Route pour récupérer le dashboard avec toutes les relations
     */
    #[Route('/api/dashboard', name: 'api_dashboard', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function dashboard(): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            return new JsonResponse(['message' => 'Non autorisé'], 401);
        }

        // On récupère l'utilisateur avec toutes ses relations via le repository
        $user = $this->userRepository->findUserWithRelations($currentUser->getId());

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur introuvable'], 404);
        }

        // Construction d'un tableau JSON simple pour le front
        $response = [
            'id' => $user->getId(),
            'username' => $user->getUserIdentifier(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'vehicles' => $user->getVehicles(),         // relation vehicles
            'preference' => $user->getPreference(),     // relation preference
            'trips' => $user->getTrips(),               // relation trips
            'reservations' => $user->getReservations(), // relation reservations
            'reviews' => $user->getReviews(),           // relation reviews
        ];

        return new JsonResponse($response);
    }
}
