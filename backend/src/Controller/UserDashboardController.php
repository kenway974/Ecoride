<?php 

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserDashboardController extends AbstractController
{
    
    #[Route('/api/user_dashboard', name: 'user_dashboard', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return new JsonResponse(['error' => 'Token manquant ou invalide'], 401);
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));

            dd($decoded);

            $identifier = $decoded->username ?? $decoded->id ??  null;

            if (!$identifier) {
                return new JsonResponse(['error' => 'Le token ne contient pas d’identifiant utilisateur'], 400);
            }

            $user = $userRepository->findUserWithRelations(null, $identifier);

            if (!$user) {
                return new JsonResponse(['error' => 'Utilisateur introuvable'], 404);
            }

            // 5. Retour des infos (non sensibles)
            return new JsonResponse([
                'user' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Token invalide ou expiré'], 403);
        }
    }
}
