<?php

namespace App\Controller;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class RefreshTokenController extends AbstractController
{
    #[Route('/api/token/refresh', name: 'api_token_refresh', methods: ['POST'])]
    public function refresh(
        Request $request,
        RefreshTokenManagerInterface $refreshTokenManager,
        JWTTokenManagerInterface $jwtManager,
        UserProviderInterface $userProvider
    ): JsonResponse {
        // Récupérer le refresh token depuis le cookie HttpOnly
        $refreshTokenValue = $request->cookies->get('REFRESH_TOKEN');

        if (!$refreshTokenValue) {
            return new JsonResponse(['message' => 'Refresh token manquant'], 401);
        }

        // Vérifier que le refresh token existe en DB
        $refreshToken = $refreshTokenManager->get($refreshTokenValue);

        $now = new \DateTime();
        if (!$refreshToken || $refreshToken->getValid() < $now) {
            return new JsonResponse(['message' => 'Refresh token invalide ou expiré'], 401);
        }


        // Récupérer l'utilisateur via le username stocké
    $username = $refreshToken->getUsername();
    try {
        $user = $userProvider->loadUserByIdentifier($username);
    } catch (\Exception $e) {
        return new JsonResponse(['message' => 'Utilisateur introuvable'], 401);
    }

        // Générer un nouveau JWT
        $token = $jwtManager->create($user);

        // Optionnel : générer un nouveau refresh token et remplacer l’ancien
        // $refreshTokenManager->delete($refreshToken);
        // $newRefreshToken = $refreshTokenManager->create();
        // $newRefreshToken->setUsername($user->getUserIdentifier());
        // $newRefreshToken->setValid(new \DateTime('+1 month'));
        // $refreshTokenManager->save($newRefreshToken);

        return new JsonResponse(['token' => $token]);
    }
}
