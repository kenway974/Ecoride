<?php 

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\JwtService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserDashboardController extends AbstractController
{
    private $jwtService;
    private $userRepository;

    public function __construct(JwtService $jwtService, UserRepository $userRepository)
    {
        $this->jwtService = $jwtService;
        $this->userRepository = $userRepository;
    }

    #[Route('/api/user_dashboard', name: 'user_dashboard', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');

        $jwt = $this->jwtService->extractToken($authHeader);

        if (!$jwt) {
            return $this->json(['error' => 'Token manquant ou invalide'], 401);
        }

        $payload = $this->jwtService->decodeToken($jwt);

        if (!$payload || !isset($payload['username'])) {
            return $this->json(['error' => 'Token invalide ou username manquant'], 401);
        }

        $username = $payload['username'];

        $user = $this->userRepository->findUserWithRelations($username);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return $this->json([
            'username' => $user->getUsername(),
            'role' => $user->getRoles(),
            'email' => $user->getEmail(),
            'vehicles' => $user->getVehicles(),
            'preference' => $user->getPreference(),
            'trips' => $user->getTrips(),
            'reservations' => $user->getReservations(),
            'reviews' => $user->getReviews(),
        ]);
    }
}
